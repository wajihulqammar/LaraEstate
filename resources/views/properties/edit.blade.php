{{-- resources/views/properties/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Property - LaraEstate')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4><i class="bi bi-pencil"></i> Edit Property</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Property Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $property->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (₨) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $property->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="property_type" class="form-label">Property Type *</label>
                                <select class="form-control @error('property_type') is-invalid @enderror" 
                                        id="property_type" name="property_type" required>
                                    @foreach($propertyTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('property_type', $property->property_type) == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('property_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <select class="form-control @error('city') is-invalid @enderror" id="city" name="city" required>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ old('city', $property->city) == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" value="{{ old('address', $property->address) }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Property Details -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="bedrooms" class="form-label">Bedrooms</label>
                                <input type="number" class="form-control @error('bedrooms') is-invalid @enderror" 
                                       id="bedrooms" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0">
                                @error('bedrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="bathrooms" class="form-label">Bathrooms</label>
                                <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" 
                                       id="bathrooms" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0">
                                @error('bathrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="area_size" class="form-label">Area Size</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('area_size') is-invalid @enderror" 
                                           id="area_size" name="area_size" value="{{ old('area_size', $property->area_size) }}" min="0" step="0.01">
                                    <select class="form-control @error('area_unit') is-invalid @enderror" name="area_unit">
                                        @foreach($areaUnits as $key => $unit)
                                            <option value="{{ $key }}" {{ old('area_unit', $property->area_unit) == $key ? 'selected' : '' }}>
                                                {{ $unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('area_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('area_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required 
                                      placeholder="Describe your property in detail...">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Current Images -->
                        @if($property->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Current Images</label>
                                <div class="row">
                                    @foreach($property->images as $image)
                                        <div class="col-md-3 mb-2">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="{{ $image->alt_text }}" class="img-fluid rounded">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                                        onclick="deleteImage({{ $image->id }})">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Add New Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">Add New Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">You can add multiple images. Maximum file size: 2MB per image</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('properties.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch(`/property-images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting image');
        });
    }
}
</script>
@endpush
@endsection