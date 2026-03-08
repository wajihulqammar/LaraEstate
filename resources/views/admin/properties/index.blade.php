{{-- resources/views/admin/properties/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Properties - Admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">Manage Properties</h2>
            <p class="text-muted">Review and manage all property listings</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group">
                <a href="{{ route('admin.properties.index') }}" 
                   class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                   All ({{ App\Models\Property::count() }})
                </a>
                <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" 
                   class="btn {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                   Pending ({{ App\Models\Property::where('status', 'pending')->count() }})
                </a>
                <a href="{{ route('admin.properties.index', ['status' => 'approved']) }}" 
                   class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                   Approved ({{ App\Models\Property::where('status', 'approved')->count() }})
                </a>
                <a href="{{ route('admin.properties.index', ['status' => 'rejected']) }}" 
                   class="btn {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                   Rejected ({{ App\Models\Property::where('status', 'rejected')->count() }})
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.properties.index') }}">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Search properties..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="city">
                            <option value="">All Cities</option>
                            @foreach(['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan'] as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="property_type">
                            <option value="">All Types</option>
                            <option value="house" {{ request('property_type') == 'house' ? 'selected' : '' }}>House</option>
                            <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="commercial" {{ request('property_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="plot" {{ request('property_type') == 'plot' ? 'selected' : '' }}>Plot</option>
                            <option value="office" {{ request('property_type') == 'office' ? 'selected' : '' }}>Office</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Properties Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Properties List</h5>
            <span class="badge bg-secondary">{{ $properties->total() }} total properties</span>
        </div>
        <div class="card-body">
            @if($properties->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Property Details</th>
                                <th>Seller</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                                <tr>
                                    <td>
                                        @if($property->featured_image)
                                            <img src="{{ asset('storage/' . $property->featured_image) }}" 
                                                 alt="{{ $property->title }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @elseif($property->images->first())
                                            <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" 
                                                 alt="{{ $property->title }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-house text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ Str::limit($property->title, 30) }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt"></i> {{ $property->city }}
                                            </small>
                                            <br>
                                            <small class="badge bg-info">{{ ucfirst($property->property_type) }}</small>
                                            @if($property->is_featured)
                                                <small class="badge bg-warning text-dark">Featured</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $property->seller->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $property->seller->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $property->formatted_price ?? 'PKR ' . number_format($property->price) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $property->status === 'approved' ? 'success' : ($property->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $property->created_at->format('M d, Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $property->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical btn-group-sm">
                                            <a href="{{ route('admin.properties.show', $property) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            
                                            @if($property->status === 'pending')
                                                <form action="{{ route('admin.properties.approve', $property) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100" 
                                                            onclick="return confirm('Approve this property?')">
                                                        <i class="bi bi-check"></i> Approve
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="deleteProperty({{ $property->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} 
                            of {{ $properties->total() }} results
                        </small>
                    </div>
                    <div>
                        {{ $properties->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-house display-1 text-muted"></i>
                    <h4 class="mt-3">No Properties Found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'status', 'city', 'property_type']))
                            No properties match your current filters. Try adjusting your search criteria.
                        @else
                            There are no properties in the system yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'city', 'property_type']))
                        <a href="{{ route('admin.properties.index') }}" class="btn btn-primary">
                            <i class="bi bi-x-circle"></i> Clear All Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteProperty(propertyId) {
    if (confirm('Are you sure you want to delete this property? This action cannot be undone and will remove all associated inquiries and chats.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/properties/${propertyId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

// Show loading state when filtering
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Filtering...';
});
</script>
@endpush
@endsection