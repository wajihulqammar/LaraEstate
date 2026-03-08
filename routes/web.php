<?php
// routes/web.php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });
    
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

// Protected User Routes - PUT THESE BEFORE PUBLIC PROPERTY ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Property Management - These specific routes come FIRST
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::get('/my-properties/{property}', [PropertyController::class, 'show'])->name('properties.show-owner');
    Route::delete('/property-images/{image}', [PropertyController::class, 'deleteImage'])->name('property-images.delete');
    
    // Start Chat from Property
    Route::post('/properties/{property}/start-chat', [ChatController::class, 'store'])->name('properties.start-chat');
    
    // Inquiries
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::post('/inquiries/{inquiry}/start-chat', [InquiryController::class, 'startChat'])->name('inquiries.start-chat');
    
    // Chats
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/messages', [ChatController::class, 'sendMessage'])->name('chats.send-message');
    Route::get('/chats/{chat}/messages', [ChatController::class, 'getMessages'])->name('chats.get-messages');
    Route::delete('/chats/{chat}', [ChatController::class, 'destroy'])->name('chats.destroy');
});

// Public Property Routes - PUT THESE AFTER PROTECTED ROUTES
Route::get('/property/{property}', [HomeController::class, 'show'])->name('properties.show');

// Public Inquiry Route (for guests and authenticated users)
Route::post('/property/{property}/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
    
    // Property Management
    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}', [AdminPropertyController::class, 'show'])->name('properties.show');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('properties.reject');
    Route::post('/properties/{property}/toggle-featured', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
    Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');
    
    // Inquiry Management
    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
    Route::delete('/inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');
    
    // Chat Management
    Route::get('/chats', [AdminChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{chat}', [AdminChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/block', [AdminChatController::class, 'block'])->name('chats.block');
    Route::post('/chats/{chat}/unblock', [AdminChatController::class, 'unblock'])->name('chats.unblock');
    Route::delete('/chats/{chat}', [AdminChatController::class, 'destroy'])->name('chats.destroy');
    Route::delete('/messages/{message}', [AdminChatController::class, 'deleteMessage'])->name('messages.destroy');
});

// Redirect routes for better UX
Route::get('/post-property', function () {
    if (auth()->check()) {
        return redirect()->route('properties.create');
    }
    return redirect()->route('login')->with('info', 'Please login to post a property.');
})->name('post-property');

Route::get('/contact-seller', function () {
    if (auth()->check()) {
        return redirect()->back()->with('info', 'Please visit the property page to contact the seller.');
    }
    return redirect()->route('login')->with('info', 'Please login to contact sellers.');
})->name('contact-seller');