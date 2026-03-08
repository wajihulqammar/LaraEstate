{{-- resources/views/admin/chats/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Chats - Admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">Manage Chats</h2>
            <p class="text-muted">Monitor and manage all chat conversations</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group">
                <a href="{{ route('admin.chats.index') }}" 
                   class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                   All Chats ({{ App\Models\Chat::count() }})
                </a>
                <a href="{{ route('admin.chats.index', ['status' => 'active']) }}" 
                   class="btn {{ request('status') === 'active' ? 'btn-success' : 'btn-outline-success' }}">
                   Active ({{ App\Models\Chat::where('status', 'active')->count() }})
                </a>
                <a href="{{ route('admin.chats.index', ['status' => 'blocked']) }}" 
                   class="btn {{ request('status') === 'blocked' ? 'btn-danger' : 'btn-outline-danger' }}">
                   Blocked ({{ App\Models\Chat::where('status', 'blocked')->count() }})
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.chats.index') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search by property title..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.chats.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Chats Grid -->
    <div class="row">
        @forelse($chats as $chat)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 {{ $chat->status === 'blocked' ? 'border-danger' : ($chat->status === 'active' ? 'border-success' : '') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-primary mb-1">
                                    <i class="bi bi-house"></i> {{ \Str::limit($chat->property->title ?? 'Unknown Property', 35) }}
                                </h6>
                                <small class="text-muted">
                                    {{ $chat->property->city ?? 'N/A' }} • 
                                    {{ $chat->property->formatted_price ?? 'Price not set' }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $chat->status === 'active' ? 'success' : ($chat->status === 'blocked' ? 'danger' : 'secondary') }} fs-6">
                                {{ ucfirst($chat->status) }}
                            </span>
                        </div>
                        
                        <!-- Participants -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-check text-success me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Buyer</small>
                                        <strong class="small">{{ \Str::limit($chat->buyer->name ?? 'Unknown', 15) }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-badge text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Seller</small>
                                        <strong class="small">{{ \Str::limit($chat->seller->name ?? 'Unknown', 15) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Latest Message -->
                        @if($chat->latestMessage ?? $chat->messages->last())
                            @php
                                $latestMessage = $chat->latestMessage ?? $chat->messages->last();
                            @endphp
                            <div class="mb-3">
                                <div class="bg-light p-2 rounded">
                                    <small class="d-block">
                                        <strong>{{ $latestMessage->sender->name ?? 'Unknown' }}:</strong>
                                        {{ \Str::limit($latestMessage->message, 60) }}
                                    </small>
                                    <small class="text-muted">{{ $latestMessage->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted">No messages yet</small>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Statistics -->
                        <div class="row mb-3 text-center">
                            <div class="col-4">
                                <small class="text-muted d-block">Messages</small>
                                <strong>{{ $chat->messages->count() }}</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Started</small>
                                <strong>{{ $chat->created_at->format('M d') }}</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Last Activity</small>
                                <strong>{{ $chat->last_message_at ? $chat->last_message_at->format('M d') : 'Never' }}</strong>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <!-- Primary Actions -->
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.chats.show', $chat) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View Chat
                                </a>
                                @if($chat->property)
                                    <a href="{{ route('admin.properties.show', $chat->property) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-house"></i> Property
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Management Actions -->
                            <div class="btn-group" role="group">
                                @if($chat->status === 'blocked')
                                    <form action="{{ route('admin.chats.unblock', $chat) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Unblock this chat?')">
                                            <i class="bi bi-check-circle"></i> Unblock
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.chats.block', $chat) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning w-100" onclick="return confirm('Block this chat?')">
                                            <i class="bi bi-ban"></i> Block
                                        </button>
                                    </form>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteChat({{ $chat->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat Status Footer -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Chat #{{ $chat->id }}
                            </small>
                            <small class="text-muted">
                                {{ $chat->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-chat-x display-1 text-muted"></i>
                        <h3 class="mt-3">No Chats Found</h3>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status']))
                                No chat conversations match your current filters.
                            @else
                                No chat conversations have been started yet.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.chats.index') }}" class="btn btn-primary">
                                <i class="bi bi-x-circle"></i> Clear All Filters
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($chats->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $chats->withQueryString()->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function deleteChat(chatId) {
    if (confirm('Are you sure you want to delete this chat? This will permanently delete all messages and cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/chats/${chatId}`;
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

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-group .btn {
    flex: 1;
}

.card.border-success {
    border-left: 4px solid #198754 !important;
}

.card.border-danger {
    border-left: 4px solid #dc3545 !important;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush
@endsection