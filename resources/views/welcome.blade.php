{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'LaraEstate - Find Your Dream Property')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Find Your Dream Property</h1>
                <p class="lead mb-4">Discover the best real estate deals in Pakistan. Buy, sell, or rent properties with ease.</p>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Search Properties</h5>
                        <form method="GET" action="{{ route('home') }}">
                            <div class="row g-2">
                                <div class="col-12">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search by title, location..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" name="city">
                                        <option value="">All Cities</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                                {{ $city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" name="property_type">
                                        <option value="">All Types</option>
                                        @foreach($propertyTypes as $key => $type)
                                            <option value="{{ $key }}" {{ request('property_type') == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="min_price" 
                                           placeholder="Min Price (₨)" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="max_price" 
                                           placeholder="Max Price (₨)" value="{{ request('max_price') }}">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i> Search Properties
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties -->
@if($featuredProperties->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Featured Properties</h2>
                <p class="text-muted">Handpicked premium properties for you</p>
            </div>
        </div>
        <div class="row">
            @foreach($featuredProperties as $property)
                <div class="col-lg-4 col-md-6 mb-4">
                    @include('partials.property-card', compact('property'))
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- All Properties -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold">Latest Properties</h2>
                <p class="text-muted">{{ $properties->total() }} properties found</p>
            </div>
            <div class="col-md-6 text-md-end">
                @auth
                    <a href="{{ route('properties.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Post Property
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Post Property
                    </a>
                @endauth
            </div>
        </div>
        
        <div class="row">
            @forelse($properties as $property)
                <div class="col-lg-4 col-md-6 mb-4">
                    @include('partials.property-card', compact('property'))
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-house-x display-1 text-muted"></i>
                    <h3 class="mt-3">No Properties Found</h3>
                    <p class="text-muted">Try adjusting your search criteria</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $properties->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="fw-bold mb-3">Ready to Find Your Next Property?</h2>
                <p class="lead mb-4">Join thousands of satisfied customers who found their perfect property with LaraEstate</p>
                @guest
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('register') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-person-plus"></i> Register Now
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </div>
                @else
                    <a href="{{ route('properties.create') }}" class="btn btn-success btn-lg">
                        <i class="bi bi-plus-circle"></i> List Your Property
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection