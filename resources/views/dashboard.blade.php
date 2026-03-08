{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - LaraEstate')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-muted">Manage your properties and communications from here</p>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
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
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ $approvedProperties }}</h3>
                            <p class="mb-0">Approved</p>
                        </div>
                        <div>
                            <i class="bi bi-check-circle display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
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
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">{{ $totalChats }}</h3>
                            <p class="mb-0">Active Chats</p>
                            @if($unreadMessages > 0)
                                <span class="badge bg-danger">{{ $unreadMessages }} new</span>
                            @endif
                        </div>
                        <div>
                            <i class="bi bi-chat-dots display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('properties.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Post New Property
                        </a>
                        <a href="{{ route('properties.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house"></i> My Properties
                        </a>
                        <a href="{{ route('chats.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-chat-dots"></i> My Chats
                            @if($unreadMessages > 0)
                                <span class="badge bg-danger">{{ $unreadMessages }}</span>
                            @endif
                        </a>
                        <a href="{{ route('inquiries.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-envelope"></i> My Inquiries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Properties -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Properties</h5>
                    <a href="{{ route('properties.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @forelse($recentProperties as $property)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            @if($property->featured_image || $property->images->first())
                                <img src="{{ asset('storage/' . ($property->featured_image ?: $property->images->first()->image_path)) }}" 
                                     alt="{{ $property->title }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-house text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ \Str::limit($property->title, 40) }}</h6>
                                <p class="text-muted small mb-1">{{ $property->formatted_price }} • {{ $property->city }}</p>
                                <span class="badge bg-{{ $property->status === 'approved' ? 'success' : ($property->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('properties.show-owner', $property) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">No properties yet. <a href="{{ route('properties.create') }}">Post your first property</a></p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Recent Chats -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Chats</h5>
                    <a href="{{ route('chats.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @forelse($recentChats as $chat)
                        @if($chat->property)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    @php
                                        $otherUser = $chat->buyer_id === auth()->id() ? $chat->seller : $chat->buyer;
                                    @endphp
                                    <h6 class="mb-1">{{ $otherUser->name }}</h6>
                                    <p class="text-muted small mb-1">{{ \Str::limit($chat->property->title, 30) }}</p>
                                    @if($chat->latestMessage)
                                        <p class="text-muted small">{{ \Str::limit($chat->latestMessage->message, 40) }}</p>
                                    @endif
                                </div>
                                <div class="text-end">
                                    @if($chat->getUnreadCount(auth()->id()) > 0)
                                        <span class="badge bg-danger">{{ $chat->getUnreadCount(auth()->id()) }}</span>
                                    @endif
                                    <div>
                                        <a href="{{ route('chats.show', $chat) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    @php
                                        $otherUser = $chat->buyer_id === auth()->id() ? $chat->seller : $chat->buyer;
                                    @endphp
                                    <h6 class="mb-1">{{ $otherUser->name }}</h6>
                                    <p class="text-muted small mb-1"><em>Property no longer available</em></p>
                                    @if($chat->latestMessage)
                                        <p class="text-muted small">{{ \Str::limit($chat->latestMessage->message, 40) }}</p>
                                    @endif
                                </div>
                                <div class="text-end">
                                    @if($chat->getUnreadCount(auth()->id()) > 0)
                                        <span class="badge bg-danger">{{ $chat->getUnreadCount(auth()->id()) }}</span>
                                    @endif
                                    <div>
                                        <a href="{{ route('chats.show', $chat) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-muted text-center py-4">No chats yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection