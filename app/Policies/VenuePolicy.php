<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venue;

class VenuePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Venue $venue): bool
    {
        return $venue->is_active || ($user && ($user->id === $venue->user_id || $user->isAdmin()));
    }

    public function create(User $user): bool
    {
        return $user->isVenueOwner() || $user->isAdmin();
    }

    public function update(User $user, Venue $venue): bool
    {
        return $user->isAdmin() || $venue->user_id === $user->id;
    }

    public function delete(User $user, Venue $venue): bool
    {
        return $user->isAdmin() || $venue->user_id === $user->id;
    }
}
