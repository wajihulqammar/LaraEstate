{{-- resources/views/admin/properties/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Property Details - Admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold">Property Details</h2>
                <p class="text-muted">Review and manage property listing</p>
            </div>
            <div>
                <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Properties
                </a>
                @if($property->status === 'approved')
                    <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-info" target="_blank">
                        <i class="bi bi-eye"></i> View Public
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

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
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-4" style="height: 400px;">
                <div class="text-center">
                    <i class="bi bi-house display-1 text-muted"></i>
                    <p class="text-muted mt-2">No images uploaded</p>
                </div>
            </div>
        @endif
        
        <!-- Property Details Card -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3 class="fw-bold mb-2">{{ $property->title }}</h3>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $property->address }}, {{ $property->city }}
                        </p>
                        <h4 class="text-success">{{ $property->formatted_price }}</h4>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $property->status === 'approved' ? 'success' : ($property->status === 'pending' ? 'warning' : 'danger') }} fs-6">
                            {{ ucfirst($property->status) }}
                        </span>
                        @if($property->is_featured)
                            <br><span class="badge bg-warning text-dark mt-1">Featured</span>
                        @endif
                    </div>
                </div>
                
                <!-- Property Stats -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6 text-center border-end">
                        <h5 class="mb-1">{{ ucfirst($property->property_type) }}</h5>
                        <small class="text-muted">Type</small>
                    </div>
                    @if($property->bedrooms)
                    <div class="col-md-3 col-6 text-center border-end">
                        <h5 class="mb-1">{{ $property->bedrooms }}</h5>
                        <small class="text-muted">Bedrooms</small>
                    </div>
                    @endif
                    @if($property->bathrooms)
                    <div class="col-md-3 col-6 text-center border-end">
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
                
                <!-- Description -->
                <h5><i class="bi bi-card-text"></i> Description</h5>
                <p class="text-justify">{{ $property->description }}</p>
                
                <hr>
                
                <!-- Property Timeline -->
                <h5><i class="bi bi-clock-history"></i> Property Timeline</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-circle-fill text-primary me-2"></i>
                        Property submitted on {{ $property->created_at->format('M d, Y \a\t H:i') }}
                    </li>
                    @if($property->updated_at != $property->created_at)
                        <li class="mb-2">
                            <i class="bi bi-circle-fill text-info me-2"></i>
                            Last updated on {{ $property->updated_at->format('M d, Y \a\t H:i') }}
                        </li>
                    @endif
                    @if($property->status === 'approved')
                        <li class="mb-2">
                            <i class="bi bi-circle-fill text-success me-2"></i>
                            Approved and published
                        </li>
                    @elseif($property->status === 'rejected')
                        <li class="mb-2">
                            <i class="bi bi-circle-fill text-danger me-2"></i>
                            Rejected
                            @if(isset($property->rejection_reason))
                                <div class="bg-light p-2 rounded mt-1">
                                    <small>Reason: {{ $property->rejection_reason }}</small>
                                </div>
                            @endif
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Seller Info & Actions -->
    <div class="col-lg-4">
        <!-- Seller Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Seller Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-person-circle display-6 text-primary me-3"></i>
                    <div>
                        <h6 class="mb-1">{{ $property->seller->name }}</h6>
                        <small class="text-muted">User ID: #{{ $property->seller->id }}</small>
                    </div>
                </div>
                
                <div class="mb-2">
                    <strong>Email:</strong> {{ $property->seller->email }}
                </div>
                @if($property->seller->phone)
                <div class="mb-2">
                    <strong>Phone:</strong> {{ $property->seller->phone }}
                </div>
                @endif
                @if($property->seller->city)
                <div class="mb-2">
                    <strong>City:</strong> {{ $property->seller->city }}
                </div>
                @endif
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $property->seller->status === 'active' ? 'success' : 'warning' }}">
                        {{ ucfirst($property->seller->status) }}
                    </span>
                </div>
                
                <div class="d-grid">
                    <a href="{{ route('admin.users.show', $property->seller) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i> View Seller Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Property Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Property Actions</h5>
            </div>
            <div class="card-body">
                @if($property->status === 'pending')
                    <div class="d-grid gap-2 mb-3">
                        <form action="{{ route('admin.properties.approve', $property) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this property?')">
                                <i class="bi bi-check-circle"></i> Approve Property
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Reject Property
                        </button>
                    </div>
                @endif
                
                <div class="d-grid gap-2 mb-3">
                    <form action="{{ route('admin.properties.toggle-featured', $property) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $property->is_featured ? 'warning' : 'outline-warning' }} w-100">
                            <i class="bi bi-star{{ $property->is_featured ? '-fill' : '' }}"></i> 
                            {{ $property->is_featured ? 'Remove Featured' : 'Make Featured' }}
                        </button>
                    </form>
                </div>
                
                <div class="d-grid">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="deleteProperty()">
                        <i class="bi bi-trash"></i> Delete Property
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Property Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $property->inquiries->count() }}</h4>
                        <small class="text-muted">Inquiries</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $property->images->count() + ($property->featured_image ? 1 : 0) }}</h4>
                        <small class="text-muted">Images</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Property Inquiries -->
@if($property->inquiries->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-envelope"></i> Property Inquiries ({{ $property->inquiries->count() }})</h5>
            </div>
            <div class="card-body">
                @foreach($property->inquiries as $inquiry)
                    <div class="d-flex align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="me-3">
                            <i class="bi bi-person-circle display-6 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $inquiry->buyer_name }}</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $inquiry->buyer_email }}
                                        @if($inquiry->buyer_phone)
                                            • {{ $inquiry->buyer_phone }}
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $inquiry->status === 'responded' ? 'success' : 'warning' }}">
                                        {{ ucfirst($inquiry->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="bg-light p-3 rounded mb-2">
                                <p class="mb-0">{{ $inquiry->message }}</p>
                            </div>
                            <small class="text-muted">
                                {{ $inquiry->created_at->diffForHumans() }}
                                @if($inquiry->responded_at)
                                    • Responded {{ $inquiry->responded_at->diffForHumans() }}
                                @endif
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Property</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.properties.reject', $property) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to reject this property?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason (Optional)</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" placeholder="Provide reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Property</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteProperty() {
    if (confirm('Are you sure you want to delete this property? This action cannot be undone and will remove all associated inquiries and chats.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.properties.destroy", $property) }}';
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection