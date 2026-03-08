{{-- resources/views/chats/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Chats - LaraEstate')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">My Chats</h2>
                    <p class="text-muted mb-0">Stay connected with buyers and sellers</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                        <i class="bi bi-chat-dots me-1"></i>{{ $chats->count() }} Active Chats
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        @forelse($chats as $chat)
            @php
                $otherUser = $chat->buyer_id === auth()->id() ? $chat->seller : $chat->buyer;
                $unreadCount = $chat->getUnreadCount(auth()->id());
            @endphp
            
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card h-100 shadow-sm border-0 {{ $unreadCount > 0 ? 'border-start border-primary border-3' : '' }} chat-card">
                    <div class="card-body p-4">
                        <!-- User Info Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary bg-gradient text-white me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-semibold">{{ $otherUser->name }}</h5>
                                    <span class="badge {{ $chat->buyer_id === auth()->id() ? 'bg-success' : 'bg-info' }} bg-opacity-75 text-white">
                                        {{ $chat->buyer_id === auth()->id() ? 'Seller' : 'Buyer' }}
                                    </span>
                                </div>
                            </div>
                            @if($unreadCount > 0)
                                <div class="position-relative">
                                    <span class="badge bg-danger pulse-animation">{{ $unreadCount }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Property Info -->
                        @if($chat->property)
                            <div class="property-info bg-light bg-opacity-50 rounded-3 p-3 mb-3">
                                <h6 class="text-primary mb-2 fw-semibold">
                                    <i class="bi bi-house-door me-1"></i>{{ \Str::limit($chat->property->title, 40) }}
                                </h6>
                                <div class="d-flex justify-content-between align-items-center text-sm">
                                    <span class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $chat->property->city }}
                                    </span>
                                    <span class="fw-semibold text-success">
                                        {{ $chat->property->formatted_price }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="property-info bg-warning bg-opacity-10 rounded-3 p-3 mb-3 border border-warning border-opacity-25">
                                <h6 class="text-warning mb-2 fw-semibold">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Property No Longer Available
                                </h6>
                                <div class="text-sm">
                                    <span class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>The property associated with this chat has been removed
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Last Message -->
                        @if($chat->latestMessage)
                            <div class="last-message mb-3">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="message-indicator bg-primary" style="width: 4px; height: 4px; border-radius: 50%; margin-top: 8px;"></div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 text-dark">
                                            <span class="fw-medium">{{ $chat->latestMessage->sender->name }}:</span>
                                            <span class="text-muted">{{ \Str::limit($chat->latestMessage->message, 50) }}</span>
                                        </p>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-clock me-1"></i>{{ $chat->latestMessage->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="last-message mb-3">
                                <p class="text-muted text-center py-2">
                                    <i class="bi bi-chat-square-dots"></i> No messages yet
                                </p>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('chats.show', $chat) }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center">
                                <i class="bi bi-chat-dots me-2"></i> Open Chat
                            </a>
                            <div class="d-flex gap-2">
                                @if($chat->property)
                                    <a href="{{ route('properties.show', $chat->property) }}" 
                                       class="btn btn-outline-secondary btn-sm flex-grow-1" target="_blank">
                                        <i class="bi bi-eye me-1"></i> View Property
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm flex-grow-1" disabled>
                                        <i class="bi bi-eye-slash me-1"></i> Property Unavailable
                                    </button>
                                @endif
                                <form action="{{ route('chats.destroy', $chat) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this chat?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="bi bi-chat-x display-4 text-muted"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-dark mb-2">No Chats Yet</h3>
                        <p class="text-muted mb-4">Start conversations by contacting property sellers and connect with potential buyers</p>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-search me-2"></i> Browse Properties
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($chats->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $chats->links() }}
        </div>
    @endif
</div>

<style>
.chat-card {
    transition: all 0.3s ease;
    border-radius: 12px !important;
}

.chat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.avatar-circle {
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.25);
}

.property-info {
    border: 1px solid rgba(13, 110, 253, 0.1);
}

.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.last-message {
    border-left: 3px solid #e9ecef;
    padding-left: 12px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.text-sm {
    font-size: 0.875rem;
}
</style>
@endsection