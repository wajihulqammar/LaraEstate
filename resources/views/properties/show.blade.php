{{-- resources/views/properties/show.blade.php --}}
@extends('layouts.app')

@section('title', $property->title . ' - LaraEstate')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Property Images -->
        <div class="col-lg-8">
            @if($property->images->count() > 0 || $property->featured_image)
                <div id="propertyCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        @if($property->featured_image)
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $property->featured_image) }}" 
                                     class="d-block w-100" alt="{{ $property->title }}" style="height: 400px; object-fit: cover;">
                            </div>
                        @endif
                        @foreach($property->images as $image)
                            <div class="carousel-item {{ !$property->featured_image && $loop->first ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     class="d-block w-100" alt="{{ $image->alt_text ?: $property->title }}" 
                                     style="height: 400px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                    @if(($property->images->count() + ($property->featured_image ? 1 : 0)) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @endif
            
            <!-- Property Details -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="fw-bold mb-2">{{ $property->title }}</h2>
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt"></i> {{ $property->address }}, {{ $property->city }}
                            </p>
                        </div>
                        @if($property->is_featured)
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-star-fill"></i> Featured
                            </span>
                        @endif
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-3 col-6 text-center">
                            <h5 class="text-primary mb-1">{{ $property->formatted_price }}</h5>
                            <small class="text-muted">Price</small>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <h5 class="mb-1">{{ ucfirst($property->property_type) }}</h5>
                            <small class="text-muted">Type</small>
                        </div>
                        @if($property->bedrooms)
                        <div class="col-md-3 col-6 text-center">
                            <h5 class="mb-1">{{ $property->bedrooms }}</h5>
                            <small class="text-muted">Bedrooms</small>
                        </div>
                        @endif
                        @if($property->bathrooms)
                        <div class="col-md-3 col-6 text-center">
                            <h5 class="mb-1">{{ $property->bathrooms }}</h5>
                            <small class="text-muted">Bathrooms</small>
                        </div>
                        @endif
                        @if($property->area_size)
                        <div class="col-md-3 col-6 text-center">
                            <h5 class="mb-1">{{ $property->area_size }}</h5>
                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $property->area_unit)) }}</small>
                        </div>
                        @endif
                    </div>
                    
                    <hr>
                    
                    <h5>Description</h5>
                    <p class="text-justify">{{ $property->description }}</p>
                </div>
            </div>
        </div>
        
        <!-- Seller Info & Contact -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Seller Information</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $property->seller->name }}</h6>
                    @if($property->seller->city)
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $property->seller->city }}
                        </p>
                    @endif
                    @if($property->seller->phone)
                        <p class="mb-2">
                            <i class="bi bi-telephone"></i> {{ $property->seller->phone }}
                        </p>
                    @endif
                    
                    @auth
                        @if(auth()->id() !== $property->seller_id)
                            <form action="{{ route('properties.start-chat', $property) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-chat-dots"></i> Chat with Seller
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-chat-dots"></i> Chat with Seller
                        </a>
                    @endauth
                    
                    <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                        <i class="bi bi-envelope"></i> Send Inquiry
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Properties -->
    @if($relatedProperties->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="fw-bold mb-4">Similar Properties in {{ $property->city }}</h3>
            </div>
            @foreach($relatedProperties as $relatedProperty)
                <div class="col-lg-3 col-md-6 mb-4">
                    @include('partials.property-card', ['property' => $relatedProperty])
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Inquiry Modal -->
<div class="modal fade" id="inquiryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('inquiries.store', $property) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Inquiry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="buyer_name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="buyer_name" name="buyer_name" 
                               value="{{ auth()->user()->name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="buyer_email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="buyer_email" name="buyer_email" 
                               value="{{ auth()->user()->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="buyer_phone" class="form-label">Your Phone (Optional)</label>
                        <input type="text" class="form-control" id="buyer_phone" name="buyer_phone" 
                               value="{{ auth()->user()->phone ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required 
                                  placeholder="Hi, I'm interested in this property. Please provide more details."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Inquiry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection