{{-- resources/views/properties/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Post Property - LaraEstate')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4><i class="bi bi-plus-circle"></i> Post New Property</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Property Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required 
                                       placeholder="e.g., Beautiful 3 Bedroom House">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (₨) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required min="0" step="1000">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="property_type" class="form-label">Property Type *</label>
                                <select class="form-control @error('property_type') is-invalid @enderror" 
                                        id="property_type" name="property_type" required>
                                    <option value="">Select Type</option>
                                    @foreach($propertyTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('property_type') == $key ? 'selected' : '' }}>
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
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>
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
                                       id="address" name="address" value="{{ old('address') }}" required 
                                       placeholder="Full address">
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
                                       id="bedrooms" name="bedrooms" value="{{ old('bedrooms') }}" min="0" max="20">
                                @error('bedrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="bathrooms" class="form-label">Bathrooms</label>
                                <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" 
                                       id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}" min="0" max="20">
                                @error('bathrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="area_size" class="form-label">Area Size</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('area_size') is-invalid @enderror" 
                                           id="area_size" name="area_size" value="{{ old('area_size') }}" min="0" step="0.01">
                                    <select class="form-control @error('area_unit') is-invalid @enderror" name="area_unit">
                                        @foreach($areaUnits as $key => $unit)
                                            <option value="{{ $key }}" {{ old('area_unit', 'sq_ft') == $key ? 'selected' : '' }}>
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
                                      placeholder="Describe your property in detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">Property Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">You can select multiple images. First image will be featured.</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('properties.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Post Property
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection