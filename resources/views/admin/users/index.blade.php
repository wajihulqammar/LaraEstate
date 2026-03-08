 
{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Users - Admin')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold">Manage Users</h2>
        <p class="text-muted">View and manage all registered users</p>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="btn-group">
            <a href="{{ route('admin.users.index') }}" 
               class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">All Users</a>
            <a href="{{ route('admin.users.index', ['status' => 'active']) }}" 
               class="btn {{ request('status') === 'active' ? 'btn-success' : 'btn-outline-success' }}">Active</a>
            <a href="{{ route('admin.users.index', ['status' => 'inactive']) }}" 
               class="btn {{ request('status') === 'inactive' ? 'btn-warning' : 'btn-outline-warning' }}">Inactive</a>
            <a href="{{ route('admin.users.index', ['status' => 'banned']) }}" 
               class="btn {{ request('status') === 'banned' ? 'btn-danger' : 'btn-outline-danger' }}">Banned</a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search users..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="city">
                        <option value="">All Cities</option>
                        @foreach(['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Gujranwala', 'Peshawar', 'Quetta', 'Sialkot'] as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Properties</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-person-circle display-6 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">ID: #{{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $user->email }}</strong>
                                    @if($user->phone)
                                        <br><small class="text-muted">{{ $user->phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $user->city ?: 'Not specified' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $user->properties->count() }} properties</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'banned' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $user->created_at->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    @if($user->status === 'banned')
                                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Unban this user?')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Ban this user?')">
                                                <i class="bi bi-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteUser({{ $user->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection