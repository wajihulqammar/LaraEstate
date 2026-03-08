{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaraEstate - Real Estate Marketplace')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* Header Styles */
        .main-navbar {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 0.75rem 0;
        }
        
        
        .navbar-brand {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff !important;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand i {
            color: #f39c12;
            margin-right: 8px;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 5px;
            padding: 8px 15px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-nav .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff !important;
        }
        
        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 4px 6px;
        }
        
        /* Footer Styles - Simplified like Zameen */
        .main-footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 40px 0 20px 0;
        }
        
        .footer-section h4 {
            color: #3498db;
            font-weight: 600;
            margin-bottom: 20px;
            
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 8px;
        }
        
        .footer-links a {
            color: #bdc3c7;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #3498db;
        }
        
        .social-icons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: #34495e;
            color: #bdc3c7;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
        }
        
        .footer-bottom {
            background: #1a252f;
            padding: 15px 0;
            margin-top: 30px;
            border-top: 1px solid #34495e;
        }
        
        .footer-bottom p {
            margin: 0;
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        
        .footer-bottom a {
            color: #3498db;
            text-decoration: none;
        }
        
        .footer-bottom a:hover {
            text-decoration: underline;
        }
        
        /* Alert Improvements */
        .alert {
            border: none;
            border-radius: 10px;
            font-weight: 500;
        }
        
        .alert-dismissible .btn-close {
            padding: 0.75rem;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Enhanced Navigation -->
    <nav class="navbar navbar-expand-lg main-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-house-door-fill"></i>LaraEstate
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('properties.create') ? 'active' : '' }}" href="{{ route('properties.create') }}">
                                <i class="bi bi-plus-circle me-1"></i>Post Property
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('chats.index') ? 'active' : '' }}" href="{{ route('chats.index') }}">
                                <i class="bi bi-chat-dots me-1"></i>Messages
                                @if(auth()->user()->receivedMessages()->unread()->count() > 0)
                                    <span class="badge bg-danger ms-1">{{ auth()->user()->receivedMessages()->unread()->count() }}</span>
                                @endif
                            </a>
                        </li>
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        </li>
                    @endguest
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">
                                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                                    </h6>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('properties.index') }}">
                                        <i class="bi bi-building me-2"></i>My Properties
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Alert Messages -->
    @if(session('success') || session('error') || session('info') || session('warning'))
        <div class="container mt-3">
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
    
    <!-- Main Content -->
    <main class="container-fluid py-4">
        @yield('content')
    </main>
    
    <!-- Simplified Footer (Zameen-style) -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <h4><i class="bi bi-house-door-fill me-2"></i>LaraEstate</h4>
                        <p class="text-muted mb-3"style="color:white !important;">Pakistan's trusted real estate platform. Find, buy, sell and rent properties across Pakistan with confidence.</p>
                        
                        <!-- Social Icons -->
                        <div class="social-icons">
                            <a href="#" class="social-icon" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="social-icon" title="Twitter">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="social-icon" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="social-icon" title="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-section">
                        <h6>Quick Links</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a href="{{ route('properties.create') }}">Post Property</a></li>
                                <li><a href="{{ route('properties.index') }}">My Properties</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Login</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
                            @endauth
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Property Types -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h6>Property Types</h6>
                        <ul class="footer-links">
                            <li><a href="#">Houses for Sale</a></li>
                            <li><a href="#">Flats for Sale</a></li>
                            <li><a href="#">Plots for Sale</a></li>
                            <li><a href="#">Commercial Properties</a></li>
                            <li><a href="#">Houses for Rent</a></li>
                            <li><a href="#">Flats for Rent</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h6>Contact Info</h6>
                        <div class="contact-info">
                            <p class="text-muted mb-2" style="color:white !important;">
                                <i class="bi bi-geo-alt me-2"></i>
                                4118,4th Floor Giga Mall<br>Islamabad, Pakistan
                            </p>
                            <p class="text-muted mb-2"style="color:white !important;">
                                <i class="bi bi-telephone me-2"></i>
                                +92 312-1524710
                            </p>
                            <p class="text-muted mb-0"style="color:white !important;">
                                <i class="bi bi-envelope me-2"></i>
                                info@laraestate.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; {{ date('Y') }} LaraEstate. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>
                            <a href="#">Privacy Policy</a> | 
                            <a href="#">Terms of Service</a> | 
                            <a href="#">Sitemap</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && !alert.classList.contains('fade')) {
                        alert.classList.add('fade');
                    }
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }, 5000);
            });
        });
        
        // Add active class to current navigation item
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = window.location.pathname;
            const menuItems = document.querySelectorAll('.navbar-nav .nav-link');
            
            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentLocation) {
                    item.classList.add('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>