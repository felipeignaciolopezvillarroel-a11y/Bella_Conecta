<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Stylist;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct(private AvailabilityService $availability) {}

    public function index()
    {
        $bookings = auth()->user()
            ->bookings()
            ->with(['service', 'stylist'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')->orderBy('start_time')
            ->get();

        return view('pages.bookings.index', compact('bookings'));
    }

    public function create(Service $service)
    {
        $stylists = Stylist::where('active', true)->orderBy('name')->get();
        return view('pages.bookings.create', compact('service', 'stylists'));
    }

    public function slots(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date'       => 'required|date|after_or_equal:today',
            'stylist_id' => 'nullable|exists:stylists,id',
        ]);

        $date = Carbon::parse($request->date);

        if ($date->gt(now()->addDays(config('salon.max_days_ahead')))) {
            return response()->json(['slots' => [], 'message' => 'Fecha demasiado lejana']);
        }

        $slots = $this->availability->getSlots(
            $date,
            (int) $request->service_id,
            $request->stylist_id ? (int) $request->stylist_id : null,
        );

        return response()->json(['slots' => $slots]);
    }

    /**
     * AJAX: devuelve la disponibilidad de un mes completo
     * (para pintar el calendario con días bloqueados).
     */
    public function monthAvailability(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'year'       => 'required|integer|min:2020|max:2100',
            'month'      => 'required|integer|min:1|max:12',
            'stylist_id' => 'nullable|exists:stylists,id',
        ]);

        $days = $this->availability->getMonthAvailability(
            (int) $request->year,
            (int) $request->month,
            (int) $request->service_id,
            $request->stylist_id ? (int) $request->stylist_id : null,
        );

        return response()->json(['days' => $days]);
    }

    public function store(Request $request, Service $service)
    {
        $data = $request->validate([
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'stylist_id' => 'nullable|exists:stylists,id',
            'notes'      => 'nullable|string|max:500',
        ]);

        $date      = Carbon::parse($data['date']);
        $startTime = Carbon::parse($date->toDateString() . ' ' . $data['start_time']);
        $endTime   = $startTime->copy()->addMinutes($service->duration_minutes);
        $stylistId = $data['stylist_id'] ?? null;

        // 🔒 LÍMITE POR USUARIO — máximo N reservas activas
        $maxPerUser = config('salon.max_active_bookings_per_user', 3);
        $activeCount = Booking::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('date', '>=', now()->toDateString())
            ->count();

        if ($activeCount >= $maxPerUser) {
            throw ValidationException::withMessages([
                'limit' => "Ya tienes {$maxPerUser} reservas activas. Cancela alguna antes de crear otra.",
            ]);
        }

        // ⚠️ Evitar que el mismo usuario reserve 2 veces el mismo día
        $alreadyOnDay = Booking::where('user_id', auth()->id())
            ->whereDate('date', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($alreadyOnDay) {
            throw ValidationException::withMessages([
                'date' => 'Ya tienes una reserva ese día. Cancélala o elige otra fecha.',
            ]);
        }

        try {
            $booking = DB::transaction(function () use ($data, $date, $startTime, $endTime, $stylistId, $service) {

                // Bloquea filas del mismo día (y estilista si aplica)
                $query = Booking::whereDate('date', $date->toDateString())
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->lockForUpdate();

                if ($stylistId) {
                    $query->where('stylist_id', $stylistId);
                }

                // Comparar por string HH:MM:SS para evitar problemas de Carbon
                $startStr = $startTime->format('H:i:s');
                $endStr   = $endTime->format('H:i:s');

                $conflicts = $query->get()->filter(function ($b) use ($startStr, $endStr) {
                    $bStart = \Illuminate\Support\Carbon::parse($b->start_time)->format('H:i:s');
                    $bEnd   = \Illuminate\Support\Carbon::parse($b->end_time)->format('H:i:s');
                    return $startStr < $bEnd && $endStr > $bStart;
                });

                if ($conflicts->isNotEmpty()) {
                    throw ValidationException::withMessages([
                        'start_time' => 'Ese horario ya está reservado. Elige otro.',
                    ]);
                }

                return Booking::create([
                    'user_id'    => auth()->id(),
                    'service_id' => $service->id,
                    'stylist_id' => $stylistId,
                    'date'       => $date->toDateString(),
                    'start_time' => $startStr,
                    'end_time'   => $endStr,
                    'status'     => 'confirmed',
                    'notes'      => $data['notes'] ?? null,
                    'code'       => Booking::generateCode(),
                ]);
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Captura específica del índice único
            return back()->withErrors([
                'start_time' => 'Ese horario acaba de ser tomado por otra persona. Elige otro.',
            ])->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            if (
                str_contains($e->getMessage(), 'UNIQUE constraint failed') ||
                str_contains($e->getMessage(), 'unique_active_slot')
            ) {
                return back()->withErrors([
                    'start_time' => 'Ese horario acaba de ser tomado. Elige otro.',
                ])->withInput();
            }
            throw $e;
        }

        return redirect()
            ->route('bookings.index')
            ->with('success', "¡Reserva confirmada! Tu código es {$booking->code}");
    }

    public function destroy(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Reserva cancelada.');
    }
}
