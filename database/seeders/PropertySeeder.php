<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();
        $cities = ['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan'];
        $propertyTypes = ['house', 'apartment', 'commercial', 'plot', 'office'];
        $areaUnits = ['marla', 'kanal', 'sq_ft', 'sq_yard'];
        
        $properties = [
            [
                'title' => 'Beautiful 3 Bedroom House in DHA Lahore',
                'description' => 'A stunning 3 bedroom house located in the heart of DHA Lahore. This property features modern amenities, a spacious garden, and is perfect for families. The house includes 3 bedrooms, 2 bathrooms, a large living room, kitchen, and parking space for 2 cars.',
                'price' => 15000000,
                'city' => 'Lahore',
                'address' => 'DHA Phase 5, Block Y, Lahore',
                'property_type' => 'house',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_size' => 10,
                'area_unit' => 'marla',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'title' => 'Modern Apartment in Clifton Karachi',
                'description' => 'Luxury apartment in Clifton with sea view. Features include modern kitchen, spacious bedrooms, and 24/7 security. Located near schools, hospitals, and shopping centers.',
                'price' => 12000000,
                'city' => 'Karachi',
                'address' => 'Clifton Block 2, Karachi',
                'property_type' => 'apartment',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_size' => 1200,
                'area_unit' => 'sq_ft',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'title' => 'Commercial Plaza in Blue Area Islamabad',
                'description' => 'Prime commercial property in Blue Area, Islamabad. Perfect for offices, shops, or business centers. High foot traffic area with excellent connectivity.',
                'price' => 50000000,
                'city' => 'Islamabad',
                'address' => 'Blue Area, Islamabad',
                'property_type' => 'commercial',
                'bedrooms' => null,
                'bathrooms' => 4,
                'area_size' => 5000,
                'area_unit' => 'sq_ft',
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'title' => '1 Kanal Plot in Bahria Town Rawalpindi',
                'description' => 'Residential plot in Bahria Town Phase 8, Rawalpindi. Corner plot with good location, ready for construction. All utilities available.',
                'price' => 8000000,
                'city' => 'Rawalpindi',
                'address' => 'Bahria Town Phase 8, Rawalpindi',
                'property_type' => 'plot',
                'bedrooms' => null,
                'bathrooms' => null,
                'area_size' => 1,
                'area_unit' => 'kanal',
                'status' => 'pending',
                'is_featured' => false,
            ],
            [
                'title' => '4 Bedroom Villa in Faisalabad',
                'description' => 'Spacious 4 bedroom villa with garden, garage, and modern amenities. Located in a quiet residential area with easy access to main roads.',
                'price' => 18000000,
                'city' => 'Faisalabad',
                'address' => 'Civil Lines, Faisalabad',
                'property_type' => 'house',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_size' => 12,
                'area_unit' => 'marla',
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'title' => 'Office Space in Multan Cantt',
                'description' => 'Modern office space suitable for IT companies, consultancies, or professional services. Features include conference rooms, parking, and 24/7 security.',
                'price' => 6000000,
                'city' => 'Multan',
                'address' => 'Multan Cantt, Multan',
                'property_type' => 'office',
                'bedrooms' => null,
                'bathrooms' => 2,
                'area_size' => 2000,
                'area_unit' => 'sq_ft',
                'status' => 'pending',
                'is_featured' => false,
            ],
            [
                'title' => 'Luxury Apartment in Gulberg Lahore',
                'description' => 'Premium apartment in Gulberg with all modern facilities. Includes gym, swimming pool, and rooftop restaurant access.',
                'price' => 20000000,
                'city' => 'Lahore',
                'address' => 'Gulberg III, Main Boulevard, Lahore',
                'property_type' => 'apartment',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_size' => 1800,
                'area_unit' => 'sq_ft',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'title' => 'Budget House in Satellite Town Rawalpindi',
                'description' => 'Affordable 2 bedroom house perfect for small families. Basic amenities with potential for renovation and expansion.',
                'price' => 4500000,
                'city' => 'Rawalpindi',
                'address' => 'Satellite Town, Rawalpindi',
                'property_type' => 'house',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area_size' => 5,
                'area_unit' => 'marla',
                'status' => 'rejected',
                'is_featured' => false,
            ],
        ];

        foreach ($properties as $index => $propertyData) {
            $property = Property::create([
                'seller_id' => $users->random()->id,
                ...$propertyData,
            ]);

            // Create some sample images (you would need actual image files in storage/app/public/properties/)
            $imageCount = rand(1, 3);
            for ($i = 1; $i <= $imageCount; $i++) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => "sample/property-{$property->id}-{$i}.jpg", // Placeholder path
                    'alt_text' => $property->title . " - Image {$i}",
                    'sort_order' => $i,
                ]);
            }
        }
    }
}