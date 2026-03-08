<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        $query = Inquiry::with(['property', 'buyer']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('buyer_name', 'LIKE', "%{$search}%")
                  ->orWhere('buyer_email', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }
        
        $inquiries = $query->latest()->paginate(15);
        
        return view('admin.inquiries.index', compact('inquiries'));
    }
    
    public function show(Inquiry $inquiry)
    {
        $inquiry->load(['property.seller', 'buyer']);
        
        return view('admin.inquiries.show', compact('inquiry'));
    }
    
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        
        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }
}