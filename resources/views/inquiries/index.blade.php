@extends('layouts.app')

@section('title', 'My Inquiries - LaraEstate')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4">Property Inquiries</h2>
            <p class="text-muted">Inquiries received for your properties</p>
        </div>
    </div>
    
    @forelse($inquiries as $inquiry)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="bi bi-person-circle display-6 text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $inquiry->buyer_name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-envelope"></i> {{ $inquiry->buyer_email }}
                                    @if($inquiry->buyer_phone)
                                        • <i class="bi bi-telephone"></i> {{ $inquiry->buyer_phone }}
                                    @endif
                                </p>
                                
                                <h6 class="text-primary mb-2">
                                    <i class="bi bi-house"></i> {{ $inquiry->property->title }}
                                </h6>
                                
                                <div class="bg-light p-3 rounded mb-3">
                                    <p class="mb-0">{{ $inquiry->message }}</p>
                                </div>
                                
                                <p class="text-muted small">
                                    Received {{ $inquiry->created_at->diffForHumans() }}
                                    @if($inquiry->responded_at)
                                        • Responded {{ $inquiry->responded_at->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-md-end">
                        <div class="mb-2">
                            <span class="badge bg-{{ $inquiry->status === 'responded' ? 'success' : 'warning' }}">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </div>
                        
                        <div class="btn-group-vertical w-100">
                            <a href="{{ route('inquiries.show', $inquiry) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            
                            @if($inquiry->buyer_id && $inquiry->status === 'pending')
                                <form action="{{ route('inquiries.start-chat', $inquiry) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-chat-dots"></i> Start Chat
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('properties.show', $inquiry->property) }}" 
                               class="btn btn-outline-secondary btn-sm" target="_blank">
                                <i class="bi bi-house"></i> View Property
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-envelope-x display-1 text-muted"></i>
                <h3 class="mt-3">No Inquiries Yet</h3>
                <p class="text-muted">Inquiries for your properties will appear here</p>
                <a href="{{ route('properties.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Post a Property
                </a>
            </div>
        </div>
    @endforelse
    
    @if($inquiries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $inquiries->links() }}
        </div>
    @endif
</div>
@endsection