<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id
            || $booking->venue->user_id === $user->id
            || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isCustomer() || $user->isAdmin();
    }

    public function pay(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }
}
