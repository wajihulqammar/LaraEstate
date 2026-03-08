{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - LaraEstate')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .stats-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <!-- Brand -->
                    <div class="text-center mb-4">
                        <h4 class="text-white mb-0">
                            <i class="bi bi-shield-check-fill me-2"></i>LaraEstate
                        </h4>
                        <small class="text-white-50">Admin Panel</small>
                    </div>
                    
                    <!-- Admin Info -->
                    <div class="card bg-white bg-opacity-10 border-0 mb-4">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-person-gear display-5 text-white"></i>
                            <h6 class="text-white mt-2 mb-1">{{ auth()->user()->name }}</h6>
                            <small class="text-white-50">Administrator</small>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="nav nav-pills flex-column">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}" 
                           href="{{ route('admin.properties.index') }}">
                            <i class="bi bi-house"></i> Properties
                            @php
                                $pendingCount = \App\Models\Property::pending()->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                           href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people"></i> Users
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}" 
                           href="{{ route('admin.inquiries.index') }}">
                            <i class="bi bi-envelope"></i> Inquiries
                            @php
                                $pendingInquiries = \App\Models\Inquiry::where('status', 'pending')->count();
                            @endphp
                            @if($pendingInquiries > 0)
                                <span class="badge bg-info ms-auto">{{ $pendingInquiries }}</span>
                            @endif
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.chats.*') ? 'active' : '' }}" 
                           href="{{ route('admin.chats.index') }}">
                            <i class="bi bi-chat-dots"></i> Chats
                        </a>
                        
                        <hr class="text-white-50">
                        
                        <a class="nav-link" href="{{ route('home') }}" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i> View Website
                        </a>
                        
                        <a class="nav-link text-warning" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Header -->
                <header class="bg-white shadow-sm border-bottom mb-4">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-outline-secondary d-md-none me-3" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                                    <i class="bi bi-list"></i>
                                </button>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                                                <i class="bi bi-house-door"></i> Admin
                                            </a>
                                        </li>
                                        @if(request()->routeIs('admin.properties.*'))
                                            <li class="breadcrumb-item active">Properties</li>
                                        @elseif(request()->routeIs('admin.users.*'))
                                            <li class="breadcrumb-item active">Users</li>
                                        @elseif(request()->routeIs('admin.inquiries.*'))
                                            <li class="breadcrumb-item active">Inquiries</li>
                                        @elseif(request()->routeIs('admin.chats.*'))
                                            <li class="breadcrumb-item active">Chats</li>
                                        @else
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        @endif
                                    </ol>
                                </nav>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <!-- Quick Stats -->
                                <div class="d-none d-lg-flex align-items-center me-4">
                                    @php
                                        $totalUsers = \App\Models\User::users()->count();
                                        $totalProperties = \App\Models\Property::count();
                                        $pendingProperties = \App\Models\Property::pending()->count();
                                    @endphp
                                    <div class="me-3 text-center">
                                        <small class="text-muted d-block">Users</small>
                                        <strong class="text-primary">{{ $totalUsers }}</strong>
                                    </div>
                                    <div class="me-3 text-center">
                                        <small class="text-muted d-block">Properties</small>
                                        <strong class="text-info">{{ $totalProperties }}</strong>
                                    </div>
                                    @if($pendingProperties > 0)
                                        <div class="text-center">
                                            <small class="text-muted d-block">Pending</small>
                                            <strong class="text-warning">{{ $pendingProperties }}</strong>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- User Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                            id="userDropdown" data-bs-toggle="dropdown">
                                        <i class="bi bi-person-gear"></i> {{ auth()->user()->name }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <h6 class="dropdown-header">
                                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                                            </h6>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                                <i class="bi bi-box-arrow-up-right me-2"></i> View Website
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <form id="logout-form-header" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </header>
                
                <!-- Alert Messages -->
                @if(session('success') || session('error') || session('info') || session('warning'))
                    <div class="container-fluid">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Main Content Area -->
                <main class="container-fluid">
                    @yield('content')
                </main>
                
                <!-- Footer -->
                <footer class="bg-white border-top mt-5">
                    <div class="container-fluid py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    © {{ date('Y') }} LaraEstate Admin Panel. All rights reserved.
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">
                                    Version 1.0 | Laravel {{ app()->version() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto-hide alerts after 5 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
            
            // Update page title based on current section
            const currentPath = window.location.pathname;
            const titleElement = document.querySelector('title');
            
            if (currentPath.includes('/admin/properties')) {
                titleElement.textContent = 'Properties Management - LaraEstate Admin';
            } else if (currentPath.includes('/admin/users')) {
                titleElement.textContent = 'Users Management - LaraEstate Admin';
            } else if (currentPath.includes('/admin/inquiries')) {
                titleElement.textContent = 'Inquiries Management - LaraEstate Admin';
            } else if (currentPath.includes('/admin/chats')) {
                titleElement.textContent = 'Chats Management - LaraEstate Admin';
            }
        });
        
        // Confirmation dialogs for destructive actions
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }
        
        // Toast notification system
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }
        
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '11';
            document.body.appendChild(container);
            return container;
        }
    </script>
    
    @stack('scripts')
</body>
</html>