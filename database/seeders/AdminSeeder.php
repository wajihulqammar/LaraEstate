<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@laraestate.com',
            'password' => Hash::make('admin123'),
            'phone' => '+92300-1234567',
            'city' => 'Lahore',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@laraestate.com',
            'password' => Hash::make('superadmin123'),
            'phone' => '+92321-7654321',
            'city' => 'Islamabad',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}