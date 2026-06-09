<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\User;
use App\Models\Venue;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BookingService
{
    public function __construct(
        protected BookingRepositoryInterface $bookings,
        protected BookingCodeService $codeService,
        protected ActivityLogService $activityLog,
    ) {}

    public function create(User $user, Venue $venue, array $data): Booking
    {
        $date = $data['booking_date'];
        $startTime = $data['start_time'];
        $duration = (int) $data['duration_hours'];
        $endTime = date('H:i', strtotime($startTime) + ($duration * 3600));

        if ($this->bookings->hasConflict($venue->id, $date, $startTime, $endTime)) {
            throw new InvalidArgumentException('Slot waktu sudah dibooking. Silakan pilih waktu lain.');
        }

        $subtotal = $venue->price_per_hour * $duration;

        return DB::transaction(function () use ($user, $venue, $data, $date, $startTime, $endTime, $duration, $subtotal) {
            if ($this->bookings->hasConflict($venue->id, $date, $startTime, $endTime)) {
                throw new InvalidArgumentException('Slot waktu sudah dibooking.');
            }

            $booking = Booking::create([
                'code' => $this->codeService->generate(),
                'user_id' => $user->id,
                'venue_id' => $venue->id,
                'booking_date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $duration,
                'players_count' => $data['players_count'] ?? 1,
                'notes' => $data['notes'] ?? null,
                'status' => BookingStatus::Pending,
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            $slotStart = $startTime;
            foreach (range(1, $duration) as $hour) {
                $slotEnd = date('H:i', strtotime($slotStart) + 3600);
                $booking->details()->create([
                    'slot_start' => $slotStart,
                    'slot_end' => $slotEnd,
                    'price' => $venue->price_per_hour,
                ]);
                $slotStart = $slotEnd;
            }

            $this->activityLog->log($user, 'booking.created', $booking);

            event(new BookingCreated($booking));

            return $booking->load(['venue', 'details']);
        });
    }
}
