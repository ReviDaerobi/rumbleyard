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
        $serviceFee = 5000;
        $total = $subtotal + $serviceFee;

        return DB::transaction(function () use ($user, $venue, $data, $date, $startTime, $endTime, $duration, $subtotal, $total) {
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
                'status' => $data['status'] ?? BookingStatus::Pending,
                'subtotal' => $subtotal,
                'total' => $total,
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

    public function update(Booking $booking, array $data): Booking
    {
        $venueId = (int) ($data['venue_id'] ?? $booking->venue_id);
        $venue = Venue::findOrFail($venueId);
        $date = $data['booking_date'] ?? $booking->booking_date->format('Y-m-d');
        $startTime = $data['start_time'] ?? substr((string) $booking->start_time, 0, 5);
        $duration = (int) ($data['duration_hours'] ?? $booking->duration_hours);
        $endTime = date('H:i', strtotime($startTime) + ($duration * 3600));

        if ($this->bookings->hasConflict($venue->id, $date, $startTime, $endTime, $booking->id)) {
            throw new InvalidArgumentException('Slot waktu sudah dibooking. Silakan pilih waktu lain.');
        }

        $subtotal = $venue->price_per_hour * $duration;
        $total = $subtotal + 5000;

        return DB::transaction(function () use ($booking, $data, $venue, $date, $startTime, $endTime, $duration, $subtotal, $total) {
            $booking->update([
                'user_id' => $data['user_id'] ?? $booking->user_id,
                'venue_id' => $venue->id,
                'booking_date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $duration,
                'players_count' => $data['players_count'] ?? $booking->players_count,
                'notes' => $data['notes'] ?? $booking->notes,
                'status' => $data['status'] ?? $booking->status,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);

            $booking->details()->delete();

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

            if ($booking->payment) {
                $booking->payment->update(['amount' => $total]);
            }

            $this->activityLog->log(auth()->user(), 'booking.updated', $booking);

            return $booking->fresh(['venue', 'details', 'user', 'payment']);
        });
    }
}
