<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Employee;
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
        $user = auth()->user();

        $bookings = $user->bookings()
            ->with(['service', 'employee'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')->orderBy('start_time')
            ->get();

        $history = $user->bookings()
            ->with(['service', 'employee'])
            ->where(function ($q) {
                $q->whereDate('date', '<', now()->toDateString())
                    ->orWhere('status', 'cancelled');
            })
            ->orderByDesc('date')->orderByDesc('start_time')
            ->paginate(10);

        return view('pages.bookings.index', compact('bookings', 'history'));
    }

    public function create(Request $request, Service $service)
    {
        if (!$service->active) {
            return redirect()->route('bookings.pick')->with('error', 'Ese servicio no está disponible actualmente.');
        }

        // Solo traemos a los profesionales activos que REALIZAN este servicio
        $employees = $service->employees()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        // Si viene un empleado por la URL, lo pre-seleccionamos
        $selectedEmployeeId = null;
        if ($request->filled('employee')) {
            $selectedEmployee = Employee::where('uuid', $request->employee)->first();
            if ($selectedEmployee) {
                $selectedEmployeeId = $selectedEmployee->id;
            }
        }

        return view('pages.bookings.create', compact('service', 'employees', 'selectedEmployeeId'));
    }

    public function slots(Request $request)
    {
        $request->validate([
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date|after_or_equal:today',
            // Ahora es requerido
            'employee_id' => 'required|exists:employees,id,category,profesional',
        ], [
            'employee_id.required' => 'Debes seleccionar un estilista.',
            'employee_id.exists'   => 'El estilista seleccionado no es válido.',
        ]);

        $date = Carbon::parse($request->date);

        if ($date->gt(now()->addDays(config('salon.max_days_ahead')))) {
            return response()->json(['slots' => [], 'message' => 'Fecha demasiado lejana']);
        }

        // Verificar que el empleado esté activo Y realice este servicio
        $isValidPro = Employee::where('id', $request->employee_id)
            ->where('category', 'profesional')
            ->where('active', true)
            ->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            })
            ->exists();

        if (! $isValidPro) {
            return response()->json(['slots' => [], 'message' => 'El estilista no realiza este servicio.']);
        }

        $slots = $this->availability->getSlots(
            $date,
            (int) $request->service_id,
            (int) $request->employee_id,
        );

        return response()->json(['slots' => $slots]);
    }

    public function monthAvailability(Request $request)
    {
        $request->validate([
            'service_id'  => 'required|exists:services,id',
            'year'        => 'required|integer|min:2020|max:2100',
            'month'       => 'required|integer|min:1|max:12',
            'employee_id' => 'required|exists:employees,id,category,profesional',
        ]);

        $days = $this->availability->getMonthAvailability(
            (int) $request->year,
            (int) $request->month,
            (int) $request->service_id,
            (int) $request->employee_id,
        );

        return response()->json(['days' => $days]);
    }

    public function store(Request $request, Service $service)
    {
        $data = $request->validate([
            'date'        => 'required|date|after_or_equal:today',
            'start_time'  => 'required|date_format:H:i',
            // Ahora es requerido
            'employee_id' => 'required|exists:employees,id,category,profesional',
            'notes'       => 'nullable|string|max:500',
        ], [
            'employee_id.required' => 'Debes seleccionar un estilista.',
            'employee_id.exists'   => 'El estilista seleccionado no es válido.',
        ]);

        $date       = Carbon::parse($data['date']);
        $startTime  = Carbon::parse($date->toDateString() . ' ' . $data['start_time']);
        $endTime    = $startTime->copy()->addMinutes($service->duration_minutes);
        $employeeId = $data['employee_id'];

        // Verificar que el empleado esté activo Y realice este servicio
        $isActivePro = Employee::where('id', $employeeId)
            ->where('category', 'profesional')
            ->where('active', true)
            ->whereHas('services', function ($q) use ($service) {
                $q->where('services.id', $service->id);
            })
            ->exists();

        if (! $isActivePro) {
            throw ValidationException::withMessages([
                'employee_id' => 'El estilista seleccionado no es válido para este servicio.',
            ]);
        }

        // Límite por usuario
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

        // Evitar reserva duplicada el mismo día
        // $alreadyOnDay = Booking::where('user_id', auth()->id())
        //     ->whereDate('date', $date->toDateString())
        //     ->whereIn('status', ['pending', 'confirmed'])
        //     ->exists();

        // if ($alreadyOnDay) {
        //     throw ValidationException::withMessages([
        //         'date' => 'Ya tienes una reserva ese día. Cancélala o elige otra fecha.',
        //     ]);
        // }

        try {
            $booking = DB::transaction(function () use ($data, $date, $startTime, $endTime, $employeeId, $service) {

                // Bloquea filas del mismo día para ese estilista
                $query = Booking::whereDate('date', $date->toDateString())
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where('employee_id', $employeeId)
                    ->lockForUpdate();

                $startStr = $startTime->format('H:i:s');
                $endStr   = $endTime->format('H:i:s');

                $conflicts = $query->get()->filter(function ($b) use ($startStr, $endStr) {
                    $bStart = Carbon::parse($b->start_time)->format('H:i:s');
                    $bEnd   = Carbon::parse($b->end_time)->format('H:i:s');
                    return $startStr < $bEnd && $endStr > $bStart;
                });

                if ($conflicts->isNotEmpty()) {
                    throw ValidationException::withMessages([
                        'start_time' => 'Ese horario ya está reservado. Elige otro.',
                    ]);
                }

                return Booking::create([
                    'user_id'     => auth()->id(),
                    'service_id'  => $service->id,
                    'employee_id' => $employeeId,
                    'date'        => $date->toDateString(),
                    'start_time'  => $startStr,
                    'end_time'    => $endStr,
                    'status'      => 'confirmed',
                    'notes'       => $data['notes'] ?? null,
                    'code'        => Booking::generateCode(),
                ]);
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
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

        // Combinamos la fecha real con la hora del start_time
        $bookingDateTime = $booking->start_time->copy()->setDate(
            $booking->date->year,
            $booking->date->month,
            $booking->date->day
        );

        if (now()->gte($bookingDateTime->copy()->subHours(24))) {
            return back()->with('error', 'No puedes cancelar una reserva con menos de 24 horas de anticipación.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada correctamente.');
    }
}
