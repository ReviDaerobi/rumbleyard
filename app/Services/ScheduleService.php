<?php

namespace App\Services;

use App\Models\Venue;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Carbon\Carbon;

class ScheduleService
{
    public function __construct(
        protected BookingRepositoryInterface $bookings,
    ) {}

    public function slotsForDate(Venue $venue, string $date): array
    {
        $hours = $venue->opening_hours ?? $this->defaultHours();
        $dayKey = strtolower(Carbon::parse($date)->format('l'));
        $dayHours = $hours[$dayKey] ?? $hours['default'] ?? ['open' => '08:00', 'close' => '22:00'];

        if (($dayHours['closed'] ?? false) === true) {
            return [];
        }

        $open = Carbon::parse($date.' '.$dayHours['open']);
        $close = Carbon::parse($date.' '.$dayHours['close']);
        $duration = $venue->slot_duration_minutes;
        $slots = [];

        $booked = $this->bookings->bookedSlots($venue->id, $date);

        while ($open->copy()->addMinutes($duration)->lte($close)) {
            $slotEnd = $open->copy()->addMinutes($duration);
            $startStr = $open->format('H:i');
            $endStr = $slotEnd->format('H:i');

            $status = 'available';
            foreach ($booked as $booking) {
                $bStart = Carbon::parse($booking->start_time)->format('H:i');
                $bEnd = Carbon::parse($booking->end_time)->format('H:i');
                if ($startStr < $bEnd && $endStr > $bStart) {
                    $status = 'booked';
                    break;
                }
            }

            $slots[] = [
                'start' => $startStr,
                'end' => $endStr,
                'status' => $status,
            ];

            $open->addMinutes($duration);
        }

        return $slots;
    }

    protected function defaultHours(): array
    {
        $default = ['open' => '08:00', 'close' => '22:00'];

        return [
            'default' => $default,
            'monday' => $default,
            'tuesday' => $default,
            'wednesday' => $default,
            'thursday' => $default,
            'friday' => $default,
            'saturday' => $default,
            'sunday' => $default,
        ];
    }
}
