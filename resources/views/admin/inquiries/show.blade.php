 
{{-- resources/views/admin/inquiries/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Inquiry Details - Admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold">Inquiry Details</h2>
                <p class="text-muted">Review inquiry information and details</p>
            </div>
            <div>
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Inquiries
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Inquiry Details -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-envelope"></i> Inquiry Information</h5>
                    <span class="badge bg-{{ $inquiry->status === 'responded' ? 'success' : ($inquiry->status === 'pending' ? 'warning' : 'secondary') }} fs-6">
                        {{ ucfirst($inquiry->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Buyer Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="bi bi-person"></i> Buyer Information</h6>
                        <div class="ms-3">
                            <p class="mb-1"><strong>Name:</strong> {{ $inquiry->buyer_name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $inquiry->buyer_email }}</p>
                            @if($inquiry->buyer_phone)
                                <p class="mb-1"><strong>Phone:</strong> {{ $inquiry->buyer_phone }}</p>
                            @endif
                            @if($inquiry->buyer)
                                <p class="mb-1">
                                    <strong>Registered User:</strong> 
                                    <a href="{{ route('admin.users.show', $inquiry->buyer) }}" class="text-primary">
                                        View Profile <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </p>
                            @else
                                <p class="mb-1 text-muted"><em>Guest User (Not Registered)</em></p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6><i class="bi bi-clock-history"></i> Timeline</h6>
                        <div class="ms-3">
                            <p class="mb-1">
                                <i class="bi bi-circle-fill text-primary me-1"></i>
                                Inquiry received on {{ $inquiry->created_at->format('M d, Y \a\t H:i') }}
                            </p>
                            <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                            
                            @if($inquiry->responded_at)
                                <p class="mb-1 mt-2">
                                    <i class="bi bi-circle-fill text-success me-1"></i>
                                    Responded on {{ $inquiry->responded_at->format('M d, Y \a\t H:i') }}
                                </p>
                                <small class="text-muted">{{ $inquiry->responded_at->diffForHumans() }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Message Content -->
                <div class="mb-4">
                    <h6><i class="bi bi-chat-quote"></i> Inquiry Message</h6>
                    <div class="bg-light p-4 rounded">
                        <p class="mb-0 text-justify">{{ $inquiry->message }}</p>
                    </div>
                </div>
                
                <!-- Related Chat -->
                @if($inquiry->chat)
                    <div class="alert alert-info">
                        <i class="bi bi-chat-dots"></i> This inquiry has been converted to a chat conversation.
                        <a href="{{ route('admin.chats.show', $inquiry->chat) }}" class="alert-link">View Chat</a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Property Details -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-house"></i> Property Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
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
                        <h5 class="text-primary mb-2">{{ $inquiry->property->title }}</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $inquiry->property->address }}, {{ $inquiry->property->city }}
                        </p>
                        <h6 class="text-success mb-3">{{ $inquiry->property->formatted_price }}</h6>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <small class="text-muted">Type:</small><br>
                                <strong>{{ ucfirst($inquiry->property->property_type) }}</strong>
                            </div>
                            @if($inquiry->property->bedrooms)
                            <div class="col-sm-6">
                                <small class="text-muted">Bedrooms:</small><br>
                                <strong>{{ $inquiry->property->bedrooms }}</strong>
                            </div>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.properties.show', $inquiry->property) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Property
                            </a>
                            @if($inquiry->property->status === 'approved')
                                <a href="{{ route('properties.show', $inquiry->property) }}" 
                                   class="btn btn-outline-info btn-sm" target="_blank">
                                    <i class="bi bi-box-arrow-up-right"></i> Public View
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Property Seller -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Property Seller</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-person-circle display-6 text-primary me-3"></i>
                    <div>
                        <h6 class="mb-1">{{ $inquiry->property->seller->name }}</h6>
                        <small class="text-muted">{{ $inquiry->property->seller->email }}</small>
                    </div>
                </div>
                
                @if($inquiry->property->seller->phone)
                    <p class="mb-2"><strong>Phone:</strong> {{ $inquiry->property->seller->phone }}</p>
                @endif
                @if($inquiry->property->seller->city)
                    <p class="mb-3"><strong>City:</strong> {{ $inquiry->property->seller->city }}</p>
                @endif
                
                <div class="d-grid">
                    <a href="{{ route('admin.users.show', $inquiry->property->seller) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i> View Seller Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($inquiry->buyer && !$inquiry->chat)
                        <form action="{{ route('admin.inquiries.start-chat', $inquiry) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-chat-dots"></i> Convert to Chat
                            </button>
                        </form>
                    @endif
                    
                    <button type="button" class="btn btn-outline-danger w-100" onclick="deleteInquiry()">
                        <i class="bi bi-trash"></i> Delete Inquiry
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Related Stats</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $inquiry->property->inquiries()->count() }}</h4>
                        <small class="text-muted">Total Inquiries</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $inquiry->property->seller->properties()->count() }}</h4>
                        <small class="text-muted">Seller Properties</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteInquiry() {
    if (confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.inquiries.destroy", $inquiry) }}';
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection