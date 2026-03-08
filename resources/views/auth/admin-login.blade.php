{{-- resources/views/auth/admin-login.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Login - LaraEstate')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="bi bi-shield-check"></i> Admin Login</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Admin Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Admin Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-shield-check"></i> Admin Login
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">
                        <a href="{{ route('home') }}" class="text-muted">
                            <i class="bi bi-arrow-left"></i> Back to Website
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection