{{-- resources/views/admin/inquiries/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Inquiries - Admin')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Manage Inquiries</h2>
        <p class="text-muted">View and manage all property inquiries</p>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="btn-group">
            <a href="{{ route('admin.inquiries.index') }}" 
               class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'pending']) }}" 
               class="btn {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'responded']) }}" 
               class="btn {{ request('status') === 'responded' ? 'btn-success' : 'btn-outline-success' }}">Responded</a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inquiries.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, email, or message..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Responded</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Inquiries List -->
<div class="row">
    @forelse($inquiries as $inquiry)
        <div class="col-lg-6 mb-4">
            <div class="card h-100 {{ !$inquiry->property ? 'border-warning' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle display-6 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1">{{ $inquiry->buyer_name }}</h6>
                                <p class="text-muted small mb-0">{{ $inquiry->buyer_email }}</p>
                                @if($inquiry->buyer_phone)
                                    <p class="text-muted small mb-0">{{ $inquiry->buyer_phone }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            <span class="badge bg-{{ $inquiry->status === 'responded' ? 'success' : ($inquiry->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                            @if(!$inquiry->property)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-exclamation-triangle"></i> Property Deleted
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Property Info -->
                    @if($inquiry->property)
                        <div class="mb-3">
                            <h6 class="text-primary mb-1">
                                <i class="bi bi-house"></i> {{ \Str::limit($inquiry->property->title, 40) }}
                            </h6>
                            <p class="text-muted small mb-0">
                                {{ $inquiry->property->city }} • {{ $inquiry->property->formatted_price }}
                            </p>
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="alert alert-warning py-2 mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Property Deleted:</strong> The property associated with this inquiry has been removed.
                            </div>
                        </div>
                    @endif
                    
                    <!-- Message -->
                    <div class="bg-light p-3 rounded mb-3">
                        <p class="mb-0">{{ \Str::limit($inquiry->message, 120) }}</p>
                    </div>
                    
                    <!-- Timeline -->
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Received {{ $inquiry->created_at->diffForHumans() }}
                            @if($inquiry->responded_at)
                                <br><i class="bi bi-check-circle text-success"></i> Responded {{ $inquiry->responded_at->diffForHumans() }}
                            @endif
                        </small>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            @if($inquiry->property)
                                <a href="{{ route('admin.properties.show', $inquiry->property) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-house"></i> Property
                                </a>
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled title="Property no longer exists">
                                    <i class="bi bi-house-slash"></i> Property
                                </button>
                            @endif
                        </div>
                        
                        <div class="btn-group">
                            @if($inquiry->buyer)
                                <a href="{{ route('admin.users.show', $inquiry->buyer) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-person"></i> User
                                </a>
                            @endif
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteInquiry({{ $inquiry->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-envelope-x display-1 text-muted"></i>
                    <h3 class="mt-3">No Inquiries Found</h3>
                    <p class="text-muted">No inquiries match your current filters</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($inquiries->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $inquiries->withQueryString()->links() }}
    </div>
@endif

@push('scripts')
<script>
function deleteInquiry(inquiryId) {
    if (confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/inquiries/${inquiryId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection