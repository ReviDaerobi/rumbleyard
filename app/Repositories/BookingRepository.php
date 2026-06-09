<?php

namespace App\Repositories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BookingRepository implements BookingRepositoryInterface
{
    public function findByCode(string $code): ?Booking
    {
        return Booking::query()
            ->with(['venue.sport', 'payment', 'user'])
            ->where('code', $code)
            ->first();
    }

    public function forUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Booking::query()
            ->with(['venue.sport', 'payment'])
            ->where('user_id', $userId)
            ->latest('booking_date')
            ->paginate($perPage);
    }

    public function forVenueOwner(int $ownerId, array $filters = []): LengthAwarePaginator
    {
        $query = Booking::query()
            ->with(['venue', 'user', 'payment'])
            ->whereHas('venue', fn ($q) => $q->where('user_id', $ownerId));

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(15)->withQueryString();
    }

    public function bookedSlots(int $venueId, string $date): Collection
    {
        return Booking::query()
            ->where('venue_id', $venueId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->get(['start_time', 'end_time', 'duration_hours']);
    }

    public function hasConflict(int $venueId, string $date, string $startTime, string $endTime, ?int $exceptBookingId = null): bool
    {
        $query = Booking::query()
            ->where('venue_id', $venueId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            });

        if ($exceptBookingId) {
            $query->where('id', '!=', $exceptBookingId);
        }

        return $query->exists();
    }
}
