<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function view(User $user, Chat $chat)
    {
        return $user->id === $chat->buyer_id || 
               $user->id === $chat->seller_id ||
               $user->is_admin;
    }

    public function delete(User $user, Chat $chat)
    {
        return $user->id === $chat->buyer_id || 
               $user->id === $chat->seller_id ||
               $user->is_admin;
    }
}