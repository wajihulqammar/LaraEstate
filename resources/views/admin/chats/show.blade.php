 
{{-- resources/views/admin/chats/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Chat Details - Admin')

@section('content')
<!-- Header with Navigation -->
<div class="row mb-4">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.chats.index') }}">Chats</a></li>
                <li class="breadcrumb-item active">Chat #{{ $chat->id }}</li>
            </ol>
        </nav>
        <h2 class="fw-bold">Chat Conversation</h2>
        <p class="text-muted">Property: {{ $chat->property->title }}</p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="btn-group">
            <a href="{{ route('admin.chats.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Chats
            </a>
            <a href="{{ route('admin.properties.show', $chat->property) }}" class="btn btn-outline-info">
                <i class="bi bi-house"></i> View Property
            </a>
        </div>
    </div>
</div>

<!-- Chat Info Card -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-chat-dots"></i> Chat Information
        </h5>
        <span class="badge bg-{{ $chat->status === 'active' ? 'success' : ($chat->status === 'blocked' ? 'danger' : 'secondary') }} fs-6">
            {{ ucfirst($chat->status) }}
        </span>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Property Details -->
            <div class="col-md-6">
                <h6 class="text-primary mb-3">
                    <i class="bi bi-house"></i> Property Details
                </h6>
                <div class="mb-3">
                    @if($chat->property->image_url)
                        <img src="{{ $chat->property->image_url }}" alt="Property" class="img-thumbnail mb-2" style="height: 100px; width: 150px; object-fit: cover;">
                    @endif
                    <h6 class="fw-bold">{{ $chat->property->title }}</h6>
                    <p class="text-muted mb-1">
                        <i class="bi bi-geo-alt"></i> {{ $chat->property->city }}, {{ $chat->property->state }}
                    </p>
                    <p class="text-success fw-bold mb-0">{{ $chat->property->formatted_price }}</p>
                </div>
            </div>
            
            <!-- Participants -->
            <div class="col-md-6">
                <h6 class="text-primary mb-3">
                    <i class="bi bi-people"></i> Participants
                </h6>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-person-check text-success fs-4"></i>
                            <p class="mb-1 fw-bold">Buyer</p>
                            <p class="mb-1">{{ $chat->buyer->name }}</p>
                            <small class="text-muted">{{ $chat->buyer->email }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-person-badge text-primary fs-4"></i>
                            <p class="mb-1 fw-bold">Seller</p>
                            <p class="mb-1">{{ $chat->seller->name }}</p>
                            <small class="text-muted">{{ $chat->seller->email }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Statistics -->
        <hr>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="p-2">
                    <i class="bi bi-chat-text text-primary fs-4"></i>
                    <p class="mb-0 fw-bold">{{ $chat->messages->count() }}</p>
                    <small class="text-muted">Total Messages</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2">
                    <i class="bi bi-calendar-event text-info fs-4"></i>
                    <p class="mb-0 fw-bold">{{ $chat->created_at->format('M d, Y') }}</p>
                    <small class="text-muted">Started On</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2">
                    <i class="bi bi-clock text-warning fs-4"></i>
                    <p class="mb-0 fw-bold">{{ $chat->last_message_at ? $chat->last_message_at->format('M d, Y') : 'Never' }}</p>
                    <small class="text-muted">Last Activity</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-2">
                    <i class="bi bi-hourglass text-secondary fs-4"></i>
                    <p class="mb-0 fw-bold">{{ $chat->created_at->diffInDays(now()) }} days</p>
                    <small class="text-muted">Chat Age</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Messages -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-chat-left-text"></i> Messages ({{ $chat->messages->count() }})
                </h5>
                <div class="btn-group">
                    @if($chat->status === 'blocked')
                        <form action="{{ route('admin.chats.unblock', $chat) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Unblock this chat?')">
                                <i class="bi bi-check-circle"></i> Unblock
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.chats.block', $chat) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Block this chat?')">
                                <i class="bi bi-ban"></i> Block
                            </button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteChat({{ $chat->id }})">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if($chat->messages->count() > 0)
                    <div class="chat-container" style="height: 500px; overflow-y: auto;">
                        @foreach($chat->messages->sortBy('created_at') as $message)
                            <div class="p-3 border-bottom">
                                <div class="d-flex {{ $message->sender_id === $chat->buyer_id ? 'justify-content-start' : 'justify-content-end' }}">
                                    <div class="message-bubble {{ $message->sender_id === $chat->buyer_id ? 'buyer-message' : 'seller-message' }}" 
                                         style="max-width: 70%;">
                                        <div class="message-header d-flex align-items-center mb-2">
                                            @if($message->sender_id === $chat->buyer_id)
                                                <i class="bi bi-person-check text-success me-2"></i>
                                                <strong class="text-success">{{ $chat->buyer->name }}</strong>
                                            @else
                                                <i class="bi bi-person-badge text-primary me-2"></i>
                                                <strong class="text-primary">{{ $chat->seller->name }}</strong>
                                            @endif
                                            <small class="text-muted ms-auto">{{ $message->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                        <div class="message-content p-3 rounded {{ $message->sender_id === $chat->buyer_id ? 'bg-light' : 'bg-primary text-white' }}">
                                            <p class="mb-0">{{ $message->message }}</p>
                                        </div>
                                        @if($message->is_read)
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-check-all"></i> Read
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-chat-x display-1 text-muted"></i>
                        <h4 class="mt-3">No Messages Yet</h4>
                        <p class="text-muted">This chat conversation hasn't started yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear"></i> Admin Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Quick Actions</h6>
                        <div class="btn-group me-2 mb-2">
                            <a href="{{ route('admin.users.show', $chat->buyer) }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-person"></i> View Buyer Profile
                            </a>
                            <a href="{{ route('admin.users.show', $chat->seller) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-person"></i> View Seller Profile
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="text-muted">Export & Reports</h6>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="exportChat()">
                                <i class="bi bi-download"></i> Export Chat
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="reportChat()">
                                <i class="bi bi-flag"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

function exportChat() {
    // Add export functionality
    alert('Export functionality will be implemented');
}

function reportChat() {
    // Add report generation functionality
    alert('Report generation functionality will be implemented');
}

// Auto scroll to bottom of chat container on page load
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});
</script>
@endpush

@push('styles')
<style>
.message-bubble {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-container {
    border-left: 3px solid #e9ecef;
    border-right: 3px solid #e9ecef;
}

.buyer-message .message-content {
    border-left: 3px solid #198754;
}

.seller-message .message-content {
    border-left: 3px solid #0d6efd;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}
</style>
@endpush
@endsection