<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function store(Request $request, Property $property)
    {
        if ($property->status !== 'approved') {
            abort(404);
        }
        
        $request->validate([
            'message' => 'required|string|max:1000',
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'nullable|string|max:20',
        ]);
        
        // Check if user already has an inquiry for this property
        if (Auth::check()) {
            $existingInquiry = Inquiry::where('property_id', $property->id)
                ->where('buyer_id', Auth::id())
                ->first();
            
            if ($existingInquiry) {
                return redirect()->back()
                    ->with('error', 'You have already sent an inquiry for this property.');
            }
        }
        
        $inquiry = Inquiry::create([
            'property_id' => $property->id,
            'buyer_id' => Auth::id(),
            'message' => $request->message,
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
        ]);
        
        // If user is logged in, automatically create a chat
        if (Auth::check()) {
            $chat = Chat::firstOrCreate([
                'property_id' => $property->id,
                'buyer_id' => Auth::id(),
                'seller_id' => $property->seller_id,
            ], [
                'inquiry_id' => $inquiry->id,
                'last_message_at' => now(),
            ]);
            
            // Create initial message
            $chat->messages()->create([
                'sender_id' => Auth::id(),
                'receiver_id' => $property->seller_id,
                'message' => $request->message,
            ]);
            
            $chat->updateLastMessage();
            $inquiry->markAsResponded();
            
            return redirect()->route('chats.show', $chat)
                ->with('success', 'Your inquiry has been sent and chat started!');
        }
        
        return redirect()->back()
            ->with('success', 'Your inquiry has been sent successfully!');
    }
    
    public function index()
    {
        $this->middleware('auth');
        
        $inquiries = Inquiry::with(['property', 'buyer'])
            ->whereHas('property', function($query) {
                $query->where('seller_id', Auth::id());
            })
            ->recent()
            ->paginate(10);
        
        return view('inquiries.index', compact('inquiries'));
    }
    
    public function show(Inquiry $inquiry)
    {
        $this->authorize('view', $inquiry);
        return view('inquiries.show', compact('inquiry'));
    }
    
    public function startChat(Inquiry $inquiry)
    {
        $this->authorize('view', $inquiry);
        
        if (!$inquiry->buyer_id) {
            return redirect()->back()
                ->with('error', 'Cannot start chat with guest inquiry.');
        }
        
        $chat = Chat::firstOrCreate([
            'property_id' => $inquiry->property_id,
            'buyer_id' => $inquiry->buyer_id,
            'seller_id' => $inquiry->property->seller_id,
        ], [
            'inquiry_id' => $inquiry->id,
            'last_message_at' => now(),
        ]);
        
        $inquiry->markAsResponded();
        
        return redirect()->route('chats.show', $chat);
    }
}