<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        $query = Chat::with(['property', 'buyer', 'seller', 'latestMessage']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('property', function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }
        
        $chats = $query->recent()->paginate(15);
        
        return view('admin.chats.index', compact('chats'));
    }
    
    public function show(Chat $chat)
    {
        $chat->load(['property', 'buyer', 'seller']);
        $messages = $chat->messages()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();
        
        return view('admin.chats.show', compact('chat', 'messages'));
    }
    
    public function block(Chat $chat)
    {
        $chat->update(['status' => 'blocked']);
        
        return redirect()->back()
            ->with('success', 'Chat blocked successfully.');
    }
    
    public function unblock(Chat $chat)
    {
        $chat->update(['status' => 'active']);
        
        return redirect()->back()
            ->with('success', 'Chat unblocked successfully.');
    }
    
    public function destroy(Chat $chat)
    {
        $chat->delete();
        
        return redirect()->route('admin.chats.index')
            ->with('success', 'Chat deleted successfully.');
    }
    
    public function deleteMessage(Message $message)
    {
        $chatId = $message->chat_id;
        $message->delete();
        
        return redirect()->route('admin.chats.show', $chatId)
            ->with('success', 'Message deleted successfully.');
    }
}