<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface
{
    public function findByCode(string $code): ?Booking;

    public function forUser(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function forVenueOwner(int $ownerId, array $filters = []): LengthAwarePaginator;

    public function bookedSlots(int $venueId, string $date): Collection;

    public function hasConflict(int $venueId, string $date, string $startTime, string $endTime, ?int $exceptBookingId = null): bool;
}
