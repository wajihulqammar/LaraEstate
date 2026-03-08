<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Chat;
use App\Models\Inquiry;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        // User statistics
        $totalProperties = $user->properties()->count();
        $approvedProperties = $user->properties()->approved()->count();
        $pendingProperties = $user->properties()->pending()->count();
        
        $totalInquiries = Inquiry::whereHas('property', function($query) use ($user) {
            $query->where('seller_id', $user->id);
        })->count();
        
        $totalChats = Chat::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->count();
        
        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        // Recent data
        $recentProperties = $user->properties()
            ->with('images')
            ->latest()
            ->take(5)
            ->get();
        
        $recentChats = Chat::with(['property', 'buyer', 'seller', 'latestMessage'])
            ->where(function($query) use ($user) {
                $query->where('buyer_id', $user->id)
                      ->orWhere('seller_id', $user->id);
            })
            ->recent()
            ->take(5)
            ->get();
        
        $recentInquiries = Inquiry::with(['property', 'buyer'])
            ->whereHas('property', function($query) use ($user) {
                $query->where('seller_id', $user->id);
            })
            ->recent()
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'totalProperties', 'approvedProperties', 'pendingProperties',
            'totalInquiries', 'totalChats', 'unreadMessages',
            'recentProperties', 'recentChats', 'recentInquiries'
        ));
    }
}