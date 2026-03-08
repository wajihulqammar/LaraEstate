<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['seller', 'images'])->approved();
        
        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('city')) {
            $query->byCity($request->city);
        }
        
        if ($request->filled('property_type')) {
            $query->byType($request->property_type);
        }
        
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }
        
        $properties = $query->latest()->paginate(12);
        $featuredProperties = Property::with(['seller', 'images'])
            ->approved()
            ->where('is_featured', true)
            ->take(6)
            ->get();
        
        $cities = $this->getCities();
        $propertyTypes = $this->getPropertyTypes();
        
        return view('welcome', compact('properties', 'featuredProperties', 'cities', 'propertyTypes'));
    }
    
    public function show(Property $property)
    {
        if ($property->status !== 'approved') {
            abort(404);
        }
        
        $property->load(['seller', 'images']);
        $relatedProperties = Property::with(['images'])
            ->approved()
            ->where('city', $property->city)
            ->where('id', '!=', $property->id)
            ->take(4)
            ->get();
        
        return view('properties.show', compact('property', 'relatedProperties'));
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
}