<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Inquiry;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index()
    {
        // Statistics
        $totalUsers = User::users()->count();
        $totalProperties = Property::count();
        $pendingProperties = Property::pending()->count();
        $approvedProperties = Property::approved()->count();
        $rejectedProperties = Property::rejected()->count();
        $totalInquiries = Inquiry::count();
        $totalChats = Chat::count();
        $totalMessages = Message::count();
        
        // Recent data
        $recentUsers = User::users()->latest()->take(5)->get();
        $recentProperties = Property::with(['seller', 'images'])->latest()->take(5)->get();
        $pendingPropertiesList = Property::with(['seller', 'images'])->pending()->latest()->take(5)->get();
        
        // Monthly statistics (last 12 months)
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $month->format('M Y'),
                'users' => User::users()->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
                'properties' => Property::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
            ];
        }
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalProperties', 'pendingProperties', 'approvedProperties', 'rejectedProperties',
            'totalInquiries', 'totalChats', 'totalMessages',
            'recentUsers', 'recentProperties', 'pendingPropertiesList', 'monthlyStats'
        ));
    }
}