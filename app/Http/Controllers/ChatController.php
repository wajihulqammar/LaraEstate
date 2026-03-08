<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $chats = Chat::with(['property', 'buyer', 'seller', 'latestMessage'])
            ->where(function($query) {
                $query->where('buyer_id', Auth::id())
                      ->orWhere('seller_id', Auth::id());
            })
            ->recent()
            ->paginate(15);
        
        return view('chats.index', compact('chats'));
    }
    
    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);
        
        // Mark messages as read
        $chat->messages()
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        $messages = $chat->messages()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();
        
        $otherUser = $chat->buyer_id === Auth::id() ? $chat->seller : $chat->buyer;
        
        return view('chats.show', compact('chat', 'messages', 'otherUser'));
    }
    
    public function store(Request $request, Property $property)
    {
        if ($property->seller_id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot start a chat with yourself.');
        }
        
        $chat = Chat::firstOrCreate([
            'property_id' => $property->id,
            'buyer_id' => Auth::id(),
            'seller_id' => $property->seller_id,
        ], [
            'last_message_at' => now(),
        ]);
        
        return redirect()->route('chats.show', $chat);
    }
    
    public function sendMessage(Request $request, Chat $chat)
    {
        $this->authorize('view', $chat);
        
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $receiverId = $chat->buyer_id === Auth::id() ? $chat->seller_id : $chat->buyer_id;
        
        $message = $chat->messages()->create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);
        
        $chat->updateLastMessage();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_name' => $message->sender->name,
                    'created_at' => $message->created_at->format('M d, H:i'),
                    'is_own' => true,
                ]
            ]);
        }
        
        return redirect()->back();
    }
    
    public function getMessages(Chat $chat)
    {
        $this->authorize('view', $chat);
        
        $messages = $chat->messages()
            ->with('sender')
            ->where('created_at', '>', request('last_message_time', now()->subMinutes(5)))
            ->orderBy('created_at')
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_name' => $message->sender->name,
                    'created_at' => $message->created_at->format('M d, H:i'),
                    'is_own' => $message->sender_id === Auth::id(),
                ];
            });
        
        // Mark new messages as read
        $chat->messages()
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json($messages);
    }
    
    public function destroy(Chat $chat)
    {
        $this->authorize('delete', $chat);
        
        $chat->delete();
        
        return redirect()->route('chats.index')
            ->with('success', 'Chat deleted successfully.');
    }
}