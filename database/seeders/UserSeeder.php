<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $cities = ['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Gujranwala', 'Peshawar'];
        
        // Create sample users
        $users = [
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed@example.com',
                'phone' => '+92300-1111111',
                'city' => 'Lahore',
            ],
            [
                'name' => 'Fatima Khan',
                'email' => 'fatima@example.com',
                'phone' => '+92321-2222222',
                'city' => 'Karachi',
            ],
            [
                'name' => 'Muhammad Hassan',
                'email' => 'hassan@example.com',
                'phone' => '+92333-3333333',
                'city' => 'Islamabad',
            ],
            [
                'name' => 'Ayesha Malik',
                'email' => 'ayesha@example.com',
                'phone' => '+92345-4444444',
                'city' => 'Rawalpindi',
            ],
            [
                'name' => 'Omar Sheikh',
                'email' => 'omar@example.com',
                'phone' => '+92301-5555555',
                'city' => 'Faisalabad',
            ],
            [
                'name' => 'Zara Ahmed',
                'email' => 'zara@example.com',
                'phone' => '+92322-6666666',
                'city' => 'Multan',
            ],
            [
                'name' => 'Ali Raza',
                'email' => 'aliraza@example.com',
                'phone' => '+92334-7777777',
                'city' => 'Lahore',
            ],
            [
                'name' => 'Sara Iqbal',
                'email' => 'sara@example.com',
                'phone' => '+92346-8888888',
                'city' => 'Karachi',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'phone' => $userData['phone'],
                'city' => $userData['city'],
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);
        }
    }
}