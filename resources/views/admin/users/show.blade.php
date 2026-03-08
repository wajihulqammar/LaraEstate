 
{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'User Details - Admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold">User Details</h2>
                <p class="text-muted">View detailed information about {{ $user->name }}</p>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Users
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit User
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Information -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> User Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="bi bi-person-circle display-1 text-primary"></i>
                    <h4 class="mt-2">{{ $user->name }}</h4>
                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'banned' ? 'danger' : 'warning') }} fs-6">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Email:</strong><br>
                    <span class="text-muted">{{ $user->email }}</span>
                </div>
                
                @if($user->phone)
                <div class="mb-3">
                    <strong>Phone:</strong><br>
                    <span class="text-muted">{{ $user->phone }}</span>
                </div>
                @endif
                
                @if($user->city)
                <div class="mb-3">
                    <strong>City:</strong><br>
                    <span class="text-muted">{{ $user->city }}</span>
                </div>
                @endif
                
                <div class="mb-3">
                    <strong>Joined:</strong><br>
                    <span class="text-muted">{{ $user->created_at->format('M d, Y \a\t H:i') }}</span>
                    <br>
                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                </div>
                
                <div class="mb-3">
                    <strong>Last Updated:</strong><br>
                    <span class="text-muted">{{ $user->updated_at->format('M d, Y \a\t H:i') }}</span>
                </div>
                
                <div class="d-grid gap-2">
                    @if($user->status === 'banned')
                        <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Unban this user?')">
                                <i class="bi bi-check-circle"></i> Unban User
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Ban this user?')">
                                <i class="bi bi-ban"></i> Ban User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- User Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $user->properties->count() }}</h4>
                        <small class="text-muted">Properties</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $user->inquiries->count() }}</h4>
                        <small class="text-muted">Inquiries Sent</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Properties -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-house"></i> User Properties ({{ $user->properties->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($user->properties as $property)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        @if($property->featured_image || $property->images->first())
                            <img src="{{ asset('storage/' . ($property->featured_image ?: $property->images->first()->image_path)) }}" 
                                 alt="{{ $property->title }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-house display-6 text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $property->title }}</h6>
                            <p class="text-muted small mb-1">
                                {{ $property->formatted_price }} • {{ $property->city }} • {{ ucfirst($property->property_type) }}
                            </p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-{{ $property->status === 'approved' ? 'success' : ($property->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                                @if($property->is_featured)
                                    <span class="badge bg-warning text-dark">Featured</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.properties.show', $property) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-house-x display-4 text-muted"></i>
                        <p class="text-muted mt-2">No properties posted yet</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- User Inquiries -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-envelope"></i> Recent Inquiries ({{ $user->inquiries->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($user->inquiries->take(5) as $inquiry)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="me-3">
                            <i class="bi bi-envelope display-6 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $inquiry->property->title }}</h6>
                            <p class="text-muted small mb-1">{{ \Str::limit($inquiry->message, 100) }}</p>
                            <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $inquiry->status === 'responded' ? 'success' : 'warning' }}">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-envelope-x display-4 text-muted"></i>
                        <p class="text-muted mt-2">No inquiries sent yet</p>
                    </div>
                @endforelse
                
                @if($user->inquiries->count() > 5)
                    <div class="text-center">
                        <a href="{{ route('admin.inquiries.index', ['search' => $user->email]) }}" class="btn btn-outline-primary btn-sm">
                            View All Inquiries
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection