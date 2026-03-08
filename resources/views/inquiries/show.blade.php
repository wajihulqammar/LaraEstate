@extends('layouts.app')

@section('title', 'Inquiry Details - LaraEstate')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4><i class="bi bi-envelope"></i> Inquiry Details</h4>
                </div>
                <div class="card-body">
                    <!-- Property Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($inquiry->property->featured_image || $inquiry->property->images->first())
                                <img src="{{ asset('storage/' . ($inquiry->property->featured_image ?: $inquiry->property->images->first()->image_path)) }}" 
                                     alt="{{ $inquiry->property->title }}" class="img-fluid rounded">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="bi bi-house display-4 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5 class="text-primary">{{ $inquiry->property->title }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt"></i> {{ $inquiry->property->address }}, {{ $inquiry->property->city }}
                            </p>
                            <h6 class="text-success">{{ $inquiry->property->formatted_price }}</h6>
                            <a href="{{ route('properties.show', $inquiry->property) }}" 
                               class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="bi bi-eye"></i> View Property
                            </a>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Buyer Info -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><i class="bi bi-person"></i> Buyer Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $inquiry->buyer_name }}</p>
                                    <p><strong>Email:</strong> {{ $inquiry->buyer_email }}</p>
                                </div>
                                <div class="col-md-6">
                                    @if($inquiry->buyer_phone)
                                        <p><strong>Phone:</strong> {{ $inquiry->buyer_phone }}</p>
                                    @endif
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $inquiry->status === 'responded' ? 'success' : 'warning' }}">
                                            {{ ucfirst($inquiry->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message -->
                    <div class="mb-4">
                        <h5><i class="bi bi-chat-quote"></i> Message</h5>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $inquiry->message }}</p>
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    <div class="mb-4">
                        <h5><i class="bi bi-clock-history"></i> Timeline</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-circle-fill text-primary me-2"></i>
                                Inquiry received on {{ $inquiry->created_at->format('M d, Y \a\t H:i') }}
                            </li>
                            @if($inquiry->responded_at)
                                <li class="mb-2">
                                    <i class="bi bi-circle-fill text-success me-2"></i>
                                    Responded on {{ $inquiry->responded_at->format('M d, Y \a\t H:i') }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('inquiries.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Inquiries
                        </a>
                        
                        @if($inquiry->buyer_id && $inquiry->status === 'pending')
                            <form action="{{ route('inquiries.start-chat', $inquiry) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-chat-dots"></i> Start Chat with Buyer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection