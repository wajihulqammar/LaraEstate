<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestOrAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Allow both guests and authenticated users
        return $next($request);
    }
}