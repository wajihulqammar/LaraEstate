<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        $query = Property::with(['seller', 'images']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }
        
        $properties = $query->latest()->paginate(15);
        
        return view('admin.properties.index', compact('properties'));
    }
    
    public function show(Property $property)
    {
        $property->load(['seller', 'images', 'inquiries.buyer']);
        
        return view('admin.properties.show', compact('property'));
    }
    
    public function approve(Property $property)
    {
        $property->update(['status' => 'approved']);
        
        return redirect()->back()
            ->with('success', 'Property approved successfully.');
    }
    
    public function reject(Request $request, Property $property)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);
        
        $property->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        return redirect()->back()
            ->with('success', 'Property rejected successfully.');
    }
    
    public function toggleFeatured(Property $property)
    {
        $property->update([
            'is_featured' => !$property->is_featured
        ]);
        
        $status = $property->is_featured ? 'featured' : 'unfeatured';
        
        return redirect()->back()
            ->with('success', "Property {$status} successfully.");
    }
    
    public function destroy(Property $property)
    {
        // Delete images from storage
        foreach ($property->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
        }
        
        $property->delete();
        
        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}