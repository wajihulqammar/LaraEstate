<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function view(User $user, Inquiry $inquiry)
    {
        return $user->id === $inquiry->buyer_id || 
               $user->id === $inquiry->property->seller_id ||
               $user->is_admin;
    }

    public function create(User $user)
    {
        return !$user->is_admin;
    }
}