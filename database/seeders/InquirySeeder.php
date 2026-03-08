<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::approved()->get();
        $buyers = User::where('is_admin', false)->get();

        $inquiryMessages = [
            "Hi, I'm interested in this property. Can you provide more details about the location and amenities?",
            "Hello, is this property still available? I would like to schedule a viewing.",
            "I'm looking for a property in this area. Can we discuss the price and payment terms?",
            "This looks perfect for my family. When can I visit the property?",
            "Is the price negotiable? Also, what are the nearby facilities?",
            "I'm interested in buying this property. Can you please share more photos?",
            "Hello, can you tell me about the legal documentation and possession status?",
            "I would like to know about the neighborhood and security situation.",
        ];

        foreach ($properties->take(15) as $property) {
            $inquiryCount = rand(1, 3);
            
            for ($i = 0; $i < $inquiryCount; $i++) {
                $buyer = $buyers->random();
                
                // Ensure buyer is not the seller
                if ($buyer->id === $property->seller_id) {
                    continue;
                }

                Inquiry::create([
                    'property_id' => $property->id,
                    'buyer_id' => $buyer->id,
                    'message' => $inquiryMessages[array_rand($inquiryMessages)],
                    'buyer_name' => $buyer->name,
                    'buyer_email' => $buyer->email,
                    'buyer_phone' => $buyer->phone,
                    'status' => rand(0, 1) ? 'pending' : 'responded',
                    'responded_at' => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                ]);
            }
        }
    }
}