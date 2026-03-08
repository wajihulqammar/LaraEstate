@extends('layouts.app')

@section('title', 'My Properties - LaraEstate')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">My Properties</h2>
            <p class="text-muted">Manage all your property listings</p>
        </div>
        <a href="{{ route('properties.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Property
        </a>
    </div>
    
    @forelse($properties as $property)
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-3">
                    @if($property->featured_image || $property->images->first())
                        <img src="{{ asset('storage/' . ($property->featured_image ?: $property->images->first()->image_path)) }}" 
                             alt="{{ $property->title }}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                    @else
                        <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-house display-4 text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="card-title">{{ $property->title }}</h5>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-geo-alt"></i> {{ $property->city }} • 
                                    <i class="bi bi-tag"></i> {{ ucfirst($property->property_type) }}
                                </p>
                            </div>
                            <div class="text-end">
                                <h5 class="text-primary mb-0">{{ $property->formatted_price }}</h5>
                                <span class="badge bg-{{ $property->status === 'approved' ? 'success' : ($property->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                                @if($property->is_featured)
                                    <span class="badge bg-warning text-dark">Featured</span>
                                @endif
                            </div>
                        </div>
                        
                        <p class="card-text">{{ $property->truncated_description }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Posted {{ $property->created_at->diffForHumans() }}
                                @if($property->updated_at != $property->created_at)
                                    • Updated {{ $property->updated_at->diffForHumans() }}
                                @endif
                            </div>
                            
                            <div class="btn-group">
                                <a href="{{ route('properties.show-owner', $property) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                @if($property->status === 'approved')
                                    <a href="{{ route('properties.show', $property) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i> Public View
                                    </a>
                                @endif
                                <a href="{{ route('properties.edit', $property) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteProperty({{ $property->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-house-x display-1 text-muted"></i>
                <h3 class="mt-3">No Properties Found</h3>
                <p class="text-muted">Start by adding your first property listing</p>
                <a href="{{ route('properties.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Property
                </a>
            </div>
        </div>
    @endforelse
    
    @if($properties->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $properties->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this property? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Property</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteProperty(propertyId) {
    const form = document.getElementById('deleteForm');
    form.action = `/properties/${propertyId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
@endsection