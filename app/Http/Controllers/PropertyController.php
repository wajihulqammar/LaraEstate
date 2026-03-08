<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    public function index()
    {
        $properties = Auth::user()->properties()
            ->with('images')
            ->latest()
            ->paginate(10);
        
        return view('properties.index', compact('properties'));
    }
    
    public function create()
    {
        $cities = $this->getCities();
        $propertyTypes = $this->getPropertyTypes();
        $areaUnits = $this->getAreaUnits();
        
        return view('properties.create', compact('cities', 'propertyTypes', 'areaUnits'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string',
            'address' => 'required|string',
            'property_type' => 'required|in:house,apartment,commercial,plot,office',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_size' => 'nullable|numeric|min:0',
            'area_unit' => 'required|in:marla,kanal,sq_ft,sq_yard',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $property = Auth::user()->properties()->create($request->all());
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
                
                // Set first image as featured
                if ($index === 0) {
                    $property->update(['featured_image' => $path]);
                }
            }
        }
        
        return redirect()->route('properties.index')
            ->with('success', 'Property listed successfully! It will be reviewed by admin.');
    }
    
    public function show(Property $property)
    {
        $this->authorize('view', $property);
        $property->load(['images', 'inquiries.buyer']);
        
        return view('properties.show-owner', compact('property'));
    }
    
    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        
        $cities = $this->getCities();
        $propertyTypes = $this->getPropertyTypes();
        $areaUnits = $this->getAreaUnits();
        
        return view('properties.edit', compact('property', 'cities', 'propertyTypes', 'areaUnits'));
    }
    
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string',
            'address' => 'required|string',
            'property_type' => 'required|in:house,apartment,commercial,plot,office',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_size' => 'nullable|numeric|min:0',
            'area_unit' => 'required|in:marla,kanal,sq_ft,sq_yard',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $property->update($request->all());
        $property->update(['status' => 'pending']); // Re-submit for approval
        
        // Handle new image uploads
        if ($request->hasFile('images')) {
            $currentImageCount = $property->images()->count();
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'sort_order' => $currentImageCount + $index,
                ]);
            }
        }
        
        return redirect()->route('properties.index')
            ->with('success', 'Property updated successfully! It will be reviewed again.');
    }
    
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        
        // Delete images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $property->delete();
        
        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully.');
    }
    
    public function deleteImage(PropertyImage $image)
    {
        $this->authorize('update', $image->property);
        
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        
        return response()->json(['success' => true]);
    }
    
    private function getCities()
    {
        return [
            'Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad',
            'Multan', 'Gujranwala', 'Peshawar', 'Quetta', 'Sialkot',
            'Hyderabad', 'Sargodha', 'Bahawalpur', 'Sukkur', 'Larkana'
        ];
    }
    
    private function getPropertyTypes()
    {
        return [
            'house' => 'House',
            'apartment' => 'Apartment',
            'commercial' => 'Commercial',
            'plot' => 'Plot',
            'office' => 'Office'
        ];
    }
    
    private function getAreaUnits()
    {
        return [
            'marla' => 'Marla',
            'kanal' => 'Kanal',
            'sq_ft' => 'Square Feet',
            'sq_yard' => 'Square Yard'
        ];
    }
}