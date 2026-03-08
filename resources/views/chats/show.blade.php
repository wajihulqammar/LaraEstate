{{-- resources/views/chats/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Chat - LaraEstate')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg chat-container">
                <!-- Chat Header -->
                <div class="card-header bg-primary bg-gradient text-white border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-white bg-opacity-20 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%;">
                                <i class="bi bi-person-fill text-white fs-5"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-semibold">{{ $otherUser->name }}</h4>
                                <p class="mb-0 text-white-75">
                                    <i class="bi bi-house-door me-2"></i>{{ $chat->property->title }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('properties.show', $chat->property) }}" 
                               class="btn btn-light btn-sm" target="_blank">
                                <i class="bi bi-eye me-1"></i> View Property
                            </a>
                            <a href="{{ route('chats.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Property Info Bar -->
                <div class="bg-light border-bottom p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span class="me-3">{{ $chat->property->address }}, {{ $chat->property->city }}</span>
                                <span class="badge bg-success me-2">{{ $chat->property->formatted_price }}</span>
                                <span class="badge bg-secondary">{{ ucfirst($chat->property->property_type) }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>Last active {{ $chat->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Messages Container -->
                <div class="card-body p-0 position-relative">
                    <div id="messagesContainer" class="messages-container p-4" style="height: 500px; overflow-y: auto;">
                        @foreach($messages as $message)
                            <div class="message-wrapper d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-4">
                                <div class="message {{ $message->sender_id === auth()->id() ? 'message-own' : 'message-other' }}">
                                    @if($message->sender_id !== auth()->id())
                                        <div class="message-avatar mb-2">
                                            <div class="avatar-sm bg-secondary text-white d-inline-flex align-items-center justify-content-center rounded-circle me-2" style="width: 30px; height: 30px;">
                                                {{ substr($message->sender->name, 0, 1) }}
                                            </div>
                                            <small class="text-muted">{{ $message->sender->name }}</small>
                                        </div>
                                    @endif
                                    <div class="message-bubble p-3 {{ $message->sender_id === auth()->id() ? 'bg-primary text-white ms-5' : 'bg-light me-5' }}" style="max-width: 75%; border-radius: 20px;">
                                        <p class="mb-1 message-text">{{ $message->message }}</p>
                                        <small class="{{ $message->sender_id === auth()->id() ? 'text-white-75' : 'text-muted' }} message-time">
                                            <i class="bi bi-clock me-1"></i>{{ $message->created_at->format('M d, H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Typing Indicator -->
                        <div id="typingIndicator" class="typing-indicator d-none mb-4">
                            <div class="d-flex justify-content-start">
                                <div class="bg-light me-5 p-3 rounded-pill">
                                    <div class="typing-dots d-flex gap-1">
                                        <div class="dot bg-secondary"></div>
                                        <div class="dot bg-secondary"></div>
                                        <div class="dot bg-secondary"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Message Input -->
                <div class="card-footer bg-white border-top-0 p-4">
                    <form id="messageForm" action="{{ route('chats.send-message', $chat) }}" method="POST">
                        @csrf
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control border-0 bg-light message-input" 
                                   id="messageInput" name="message" 
                                   placeholder="Type your message..." required maxlength="1000"
                                   style="border-radius: 25px 0 0 25px !important;">
                            <button type="submit" class="btn btn-primary px-4" style="border-radius: 0 25px 25px 0 !important;">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto scroll to bottom
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Add focus effect to message input
    const messageInput = document.getElementById('messageInput');
    messageInput.addEventListener('focus', function() {
        this.parentElement.classList.add('input-group-focus');
    });
    messageInput.addEventListener('blur', function() {
        this.parentElement.classList.remove('input-group-focus');
    });
});

// Handle form submission
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Show typing indicator briefly
    showTypingIndicator();
    
    // Add message to UI immediately
    setTimeout(() => {
        hideTypingIndicator();
        addMessageToUI(message, '{{ auth()->user()->name }}', 'just now', true);
        messageInput.value = '';
        scrollToBottom();
    }, 500);
    
    // Send to server
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Message failed to send');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

function addMessageToUI(message, senderName, time, isOwn) {
    const messagesContainer = document.getElementById('messagesContainer');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message-wrapper d-flex ${isOwn ? 'justify-content-end' : 'justify-content-start'} mb-4`;
    
    let avatarHtml = '';
    if (!isOwn) {
        avatarHtml = `
            <div class="message-avatar mb-2">
                <div class="avatar-sm bg-secondary text-white d-inline-flex align-items-center justify-content-center rounded-circle me-2" style="width: 30px; height: 30px;">
                    ${senderName.charAt(0)}
                </div>
                <small class="text-muted">${senderName}</small>
            </div>
        `;
    }
    
    messageDiv.innerHTML = `
        <div class="message ${isOwn ? 'message-own' : 'message-other'}">
            ${avatarHtml}
            <div class="message-bubble p-3 ${isOwn ? 'bg-primary text-white ms-5' : 'bg-light me-5'}" style="max-width: 75%; border-radius: 20px;">
                <p class="mb-1 message-text">${message}</p>
                <small class="${isOwn ? 'text-white-75' : 'text-muted'} message-time">
                    <i class="bi bi-clock me-1"></i>${time}
                </small>
            </div>
        </div>
    `;
    
    messagesContainer.appendChild(messageDiv);
}

function showTypingIndicator() {
    document.getElementById('typingIndicator').classList.remove('d-none');
    scrollToBottom();
}

function hideTypingIndicator() {
    document.getElementById('typingIndicator').classList.add('d-none');
}

// Auto-refresh messages every 10 seconds
setInterval(function() {
    fetch(`{{ route('chats.get-messages', $chat) }}?last_message_time=${new Date().toISOString()}`)
        .then(response => response.json())
        .then(messages => {
            messages.forEach(message => {
                if (!message.is_own) {
                    addMessageToUI(message.message, message.sender_name, message.created_at, false);
                    scrollToBottom();
                }
            });
        })
        .catch(error => console.error('Error fetching new messages:', error));
}, 10000);
</script>
@endpush

<style>
.chat-container {
    border-radius: 20px !important;
    overflow: hidden;
}

.messages-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.messages-container::-webkit-scrollbar {
    width: 6px;
}

.messages-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.message-bubble {
    position: relative;
    word-wrap: break-word;
    animation: messageSlideIn 0.3s ease-out;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-own .message-bubble {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.25);
}

.message-other .message-bubble {
    background: #f8f9fa !important;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.message-input {
    box-shadow: none !important;
    font-size: 1rem;
    padding: 12px 20px;
}

.message-input:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

.input-group-focus {
    transform: scale(1.01);
    transition: transform 0.2s ease;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.avatar-circle {
    backdrop-filter: blur(10px);
}

.typing-indicator {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.typing-dots {
    align-items: center;
}

.dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    animation: typingAnimation 1.4s infinite ease-in-out;
}

.dot:nth-child(1) { animation-delay: -0.32s; }
.dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingAnimation {
    0%, 80%, 100% { 
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% { 
        transform: scale(1);
        opacity: 1;
    }
}

.card-header {
    backdrop-filter: blur(10px);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover:not(:disabled) {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .message-bubble {
        max-width: 85% !important;
    }
    
    .message-own .message-bubble {
        margin-left: 2rem !important;
    }
    
    .message-other .message-bubble {
        margin-right: 2rem !important;
    }
}
</style>
@endsection