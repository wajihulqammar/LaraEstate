 
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard - LaraEstate')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Admin Dashboard</h2>
        <p class="text-muted">Welcome to LaraEstate administration panel</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $totalUsers }}</h3>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div>
                        <i class="bi bi-people display-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary">
                <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">
                    View Details <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $totalProperties }}</h3>
                        <p class="mb-0">Total Properties</p>
                    </div>
                    <div>
                        <i class="bi bi-house display-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-info">
                <a href="{{ route('admin.properties.index') }}" class="text-white text-decoration-none">
                    View Details <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $pendingProperties }}</h3>
                        <p class="mb-0">Pending Approval</p>
                    </div>
                    <div>
                        <i class="bi bi-clock display-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning">
                <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" class="text-white text-decoration-none">
                    Review Now <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $approvedProperties }}</h3>
                        <p class="mb-0">Approved Properties</p>
                    </div>
                    <div>
                        <i class="bi bi-check-circle display-4"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-success">
                <a href="{{ route('admin.properties.index', ['status' => 'approved']) }}" class="text-white text-decoration-none">
                    View Details <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h4 class="text-danger">{{ $rejectedProperties }}</h4>
                <p class="text-muted mb-0">Rejected Properties</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <h4 class="text-info">{{ $totalInquiries }}</h4>
                <p class="text-muted mb-0">Total Inquiries</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <h4 class="text-success">{{ $totalChats }}</h4>
                <p class="text-muted mb-0">Active Chats</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h4 class="text-primary">{{ $totalMessages }}</h4>
                <p class="text-muted mb-0">Total Messages</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Users</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($recentUsers as $user)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="bi bi-person-circle display-6 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $user->name }}</h6>
                            <p class="text-muted small mb-1">{{ $user->email }}</p>
                            <small class="text-muted">Joined {{ $user->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No users yet</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Pending Properties -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending Properties</h5>
                <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="card-body">
                @forelse($pendingPropertiesList as $property)
                    <div class="d-flex align-items-center mb-3">
                        @if($property->featured_image || $property->images->first())
                            <img src="{{ asset('storage/' . ($property->featured_image ?: $property->images->first()->image_path)) }}" 
                                 alt="{{ $property->title }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-house text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ \Str::limit($property->title, 30) }}</h6>
                            <p class="text-muted small mb-1">by {{ $property->seller->name }}</p>
                            <small class="text-muted">{{ $property->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <a href="{{ route('admin.properties.show', $property) }}" class="btn btn-sm btn-outline-primary">Review</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No pending properties</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Properties -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Properties</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Seller</th>
                                <th>Price</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProperties as $property)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($property->featured_image || $property->images->first())
                                                <img src="{{ asset('storage/' . ($property->featured_image ?: $property->images->first()->image_path)) }}" 
                                                     alt="{{ $property->title }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-house text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ \Str::limit($property->title, 40) }}</h6>
                                                <small class="text-muted">{{ ucfirst($property->property_type) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $property->seller->name }}</td>
                                    <td>{{ $property->formatted_price }}</td>
                                    <td>{{ $property->city }}</td>
                                    <td>
                                        <span class="badge bg-{{ $property->status === 'approved' ? 'success' : ($property->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $property->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.properties.show', $property) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No properties yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection