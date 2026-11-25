<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Employee;
use Carbon\Carbon;

class AvailabilityService
{
    public function getSlots(Carbon $date, int $serviceId, ?int $employeeId = null): array
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

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
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

            // Detectar si los 90 min (o los que dure) se solapan con otra reserva
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

    public function getMonthAvailability(int $year, int $month, int $serviceId, ?int $employeeId = null): array
    {
        $config = config('salon');
        $service = Service::findOrFail($serviceId);
        $duration = $service->duration_minutes;

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $result = [];

        $bookingsQuery = Booking::whereBetween('date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->whereIn('status', ['pending', 'confirmed']);

        if ($employeeId) {
            $bookingsQuery->where('employee_id', $employeeId);
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
                $result[$dateStr] = ['full' => true, 'available' => 0, 'total' => 0, 'closed' => true];
                $cursor->addDay();
                continue;
            }

            [$open, $close] = $hours;
            $openTime  = Carbon::parse($dateStr . ' ' . $open);
            $closeTime = Carbon::parse($dateStr . ' ' . $close);

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