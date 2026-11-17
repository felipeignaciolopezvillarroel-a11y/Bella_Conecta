<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Stylist;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Devuelve los slots disponibles para una fecha y servicio dados.
     */
    public function getSlots(Carbon $date, int $serviceId, ?int $stylistId = null): array
    {
        $config = config('salon');
        $dayOfWeek = $date->dayOfWeek;

        if (!isset($config['hours'][$dayOfWeek]) || $config['hours'][$dayOfWeek] === null) {
            return [];
        }

        [$open, $close] = $config['hours'][$dayOfWeek];
        $slotMinutes = $config['slot_minutes'];

        $service  = Service::findOrFail($serviceId);
        $duration = $service->duration_minutes;

        $openTime  = Carbon::parse($date->toDateString() . ' ' . $open);
        $closeTime = Carbon::parse($date->toDateString() . ' ' . $close);

        // Reservas activas del día
        $query = Booking::whereDate('date', $date->toDateString())
            ->whereIn('status', ['pending', 'confirmed']);

        if ($stylistId) {
            $query->where('stylist_id', $stylistId);
        }

        $existing = $query->get(['start_time', 'end_time']);

        $slots = [];
        $cursor = $openTime->copy();

        while ($cursor->copy()->addMinutes($duration)->lte($closeTime)) {
            $slotStart = $cursor->copy();
            $slotEnd   = $cursor->copy()->addMinutes($duration);
            $isPast    = $slotStart->lt(now());

            $slotStartStr = $slotStart->format('H:i:s');
            $slotEndStr   = $slotEnd->format('H:i:s');

            $isBusy = $existing->contains(function ($b) use ($slotStartStr, $slotEndStr) {
                $bStart = Carbon::parse($b->start_time)->format('H:i:s');
                $bEnd   = Carbon::parse($b->end_time)->format('H:i:s');
                return $slotStartStr < $bEnd && $slotEndStr > $bStart;
            });

            $slots[] = [
                'time'      => $slotStart->format('H:i'),
                'label'     => $slotStart->format('g:i A'),
                'available' => !$isPast && !$isBusy,
            ];

            $cursor->addMinutes($slotMinutes);
        }

        return $slots;
    }

    /**
     * Devuelve los días de un mes con su disponibilidad general.
     * Un día se marca "full" si TODOS los slots están ocupados (o es cerrado).
     *
     * @return array<string, ['full' => bool, 'available' => int, 'total' => int]>
     */
    public function getMonthAvailability(int $year, int $month, int $serviceId, ?int $stylistId = null): array
    {
        $config = config('salon');
        $service = Service::findOrFail($serviceId);
        $duration = $service->duration_minutes;

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $result = [];

        // Trae TODAS las reservas activas del mes de una sola vez (eficiente)
        $bookingsQuery = Booking::whereBetween('date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->whereIn('status', ['pending', 'confirmed']);

        if ($stylistId) {
            $bookingsQuery->where('stylist_id', $stylistId);
        }

        $bookings = $bookingsQuery->get(['date', 'start_time', 'end_time'])->groupBy(function ($b) {
            return Carbon::parse($b->date)->toDateString();
        });

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $dayOfWeek = $cursor->dayOfWeek;
            $dateStr   = $cursor->toDateString();
            $hours     = $config['hours'][$dayOfWeek] ?? null;

            if (!$hours) {
                // Día cerrado
                $result[$dateStr] = ['full' => true, 'available' => 0, 'total' => 0, 'closed' => true];
                $cursor->addDay();
                continue;
            }

            [$open, $close] = $hours;
            $openTime  = Carbon::parse($dateStr . ' ' . $open);
            $closeTime = Carbon::parse($dateStr . ' ' . $close);

            // Reservas activas del día
            $dayBookings = $bookings->get($dateStr, collect());

            $total     = 0;
            $available = 0;
            $slotCursor = $openTime->copy();

            while ($slotCursor->copy()->addMinutes($duration)->lte($closeTime)) {
                $total++;

                $slotStart    = $slotCursor->copy();
                $slotEnd      = $slotCursor->copy()->addMinutes($duration);
                $slotStartStr = $slotStart->format('H:i:s');
                $slotEndStr   = $slotEnd->format('H:i:s');
                $isPast       = $slotStart->lt(now());

                $isBusy = $dayBookings->contains(function ($b) use ($slotStartStr, $slotEndStr) {
                    $bStart = Carbon::parse($b->start_time)->format('H:i:s');
                    $bEnd   = Carbon::parse($b->end_time)->format('H:i:s');
                    return $slotStartStr < $bEnd && $slotEndStr > $bStart;
                });

                if (!$isPast && !$isBusy) {
                    $available++;
                }

                $slotCursor->addMinutes($config['slot_minutes']);
            }

            $result[$dateStr] = [
                'full'      => $available === 0,
                'available' => $available,
                'total'     => $total,
                'closed'    => false,
            ];

            $cursor->addDay();
        }

        return $result;
    }
}