<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venue;
use App\Models\Amenity;

class AmenityPolicy
{
    protected function isOwner(User $user, Venue $venue): bool
    {
        return $venue->owner_id === $user->id;
    }

    protected function isManager(User $user, Venue $venue): bool
    {
        return $venue->managers()
                ->where('user_id', $user->id)
                ->exists();
    }

    protected function isOwnerOrManager(User $user, Venue $venue): bool
    {
        return $this->isOwner($user, $venue) || $this->isManager($user, $venue);
    }

    public function create(User $user, Venue $venue): bool
    {
        return $this->isOwnerOrManager($user, $venue);
    }

    public function update(User $user, Amenity $amenity): bool
    {
        return $this->isOwnerOrManager($user, $amenity->venue);
    }

    public function delete(User $user, Amenity $amenity): bool
    {
        return $this->isOwnerOrManager($user, $amenity->venue);
    }
}
