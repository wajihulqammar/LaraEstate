<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $respondedInquiries = Inquiry::where('status', 'responded')
            ->whereNotNull('buyer_id')
            ->with(['property', 'buyer'])
            ->get();

        $sampleMessages = [
            "Hello! I saw your inquiry about the property.",
            "Yes, the property is still available. Would you like to schedule a viewing?",
            "Thank you for your interest. When would be a good time to visit?",
            "The price is slightly negotiable. What's your budget?",
            "Sure, I can arrange a viewing this weekend. What time works for you?",
            "The property has all modern amenities and is in a great location.",
            "All legal documentation is clear and ready for transfer.",
            "The neighborhood is very safe with 24/7 security.",
            "I can show you more pictures. Do you have WhatsApp?",
            "Let me know if you have any other questions about the property.",
        ];

        foreach ($respondedInquiries->take(10) as $inquiry) {
            // Create chat
            $chat = Chat::create([
                'property_id' => $inquiry->property_id,
                'buyer_id' => $inquiry->buyer_id,
                'seller_id' => $inquiry->property->seller_id,
                'inquiry_id' => $inquiry->id,
                'status' => 'active',
                'last_message_at' => now()->subDays(rand(1, 7)),
            ]);

            // Create some messages
            $messageCount = rand(3, 8);
            
            for ($i = 0; $i < $messageCount; $i++) {
                $isFromSeller = $i % 2 === 0; // Alternate between seller and buyer
                
                Message::create([
                    'chat_id' => $chat->id,
                    'sender_id' => $isFromSeller ? $chat->seller_id : $chat->buyer_id,
                    'receiver_id' => $isFromSeller ? $chat->buyer_id : $chat->seller_id,
                    'message' => $sampleMessages[array_rand($sampleMessages)],
                    'is_read' => rand(0, 1),
                    'read_at' => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                    'created_at' => now()->subDays(rand(1, 7))->addHours($i),
                ]);
            }

            // Update last message time
            $chat->update([
                'last_message_at' => $chat->messages()->latest()->first()->created_at,
            ]);
        }
    }
}