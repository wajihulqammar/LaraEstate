<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        $query = User::users()->with('properties');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function show(User $user)
    {
        if ($user->is_admin) {
            abort(403);
        }
        
        $user->load(['properties.images', 'inquiries.property']);
        
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        if ($user->is_admin) {
            abort(403);
        }
        
        $cities = $this->getCities();
        return view('admin.users.edit', compact('user', 'cities'));
    }
    
    public function update(Request $request, User $user)
    {
        if ($user->is_admin) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,banned',
            'password' => 'nullable|confirmed|min:8',
        ]);
        
        $data = $request->only(['name', 'email', 'phone', 'city', 'status']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
    
    public function destroy(User $user)
    {
        if ($user->is_admin) {
            abort(403);
        }
        
        // Check if user has active properties or chats
        if ($user->properties()->exists() || $user->sentMessages()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete user with existing properties or messages.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
    
    public function ban(User $user)
    {
        if ($user->is_admin) {
            abort(403);
        }
        
        $user->update(['status' => 'banned']);
        
        return redirect()->back()
            ->with('success', 'User banned successfully.');
    }
    
    public function unban(User $user)
    {
        if ($user->is_admin) {
            abort(403);
        }
        
        $user->update(['status' => 'active']);
        
        return redirect()->back()
            ->with('success', 'User unbanned successfully.');
    }
    
    private function getCities()
    {
        return [
            'Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad',
            'Multan', 'Gujranwala', 'Peshawar', 'Quetta', 'Sialkot',
            'Hyderabad', 'Sargodha', 'Bahawalpur', 'Sukkur', 'Larkana'
        ];
    }
}