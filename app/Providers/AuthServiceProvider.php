<?php

namespace App\Providers;

use App\Models\Property;
use App\Models\Inquiry;
use App\Models\Chat;
use App\Policies\PropertyPolicy;
use App\Policies\InquiryPolicy;
use App\Policies\ChatPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Property::class => PropertyPolicy::class,
        Inquiry::class => InquiryPolicy::class,
        Chat::class => ChatPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}