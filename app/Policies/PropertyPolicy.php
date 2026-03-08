<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function view(User $user, Property $property)
    {
        return $user->id === $property->seller_id || $user->is_admin;
    }

    public function create(User $user)
    {
        return !$user->is_admin;
    }

    public function update(User $user, Property $property)
    {
        return $user->id === $property->seller_id;
    }

    public function delete(User $user, Property $property)
    {
        return $user->id === $property->seller_id || $user->is_admin;
    }
}