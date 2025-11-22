<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\Family\AuthController as FamilyAuthController;
use App\Http\Controllers\Family\ForgotPasswordController;
use App\Http\Controllers\Family\ResetPasswordController;
use App\Http\Controllers\Family\DashboardController as FamilyDashboardController;
use App\Http\Controllers\Family\NeedController as FamilyNeedController;
use App\Http\Controllers\Family\ProfileController as FamilyProfileController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\NeedController as AdminNeedController;
use App\Http\Controllers\Admin\NeedTypeController;
use App\Http\Controllers\Admin\HelperController as AdminHelperController;
use App\Http\Controllers\Family\HelperController as FamilyHelperController;
use App\Http\Controllers\DemoController;

// Landing and Contact Pages
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Calendar Routes (Public Community Calendar)
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/{need}', [CalendarController::class, 'show'])->name('calendar.show');

// Helper Sign-Up Routes (For signing up to help with a need)
Route::get('/help/{need}/create', [HelpController::class, 'create'])->name('help.create');
Route::post('/help/{need}', [HelpController::class, 'store'])->name('help.store');

// Family Authentication and Management Routes
Route::prefix('family')->group(function () {
    // Public family routes
    Route::get('/login', [FamilyAuthController::class, 'showLoginForm'])->name('family.login');
    Route::post('/login', [FamilyAuthController::class, 'login']);
    Route::get('/register', [FamilyAuthController::class, 'showRegisterForm'])->name('family.register');
    Route::post('/register', [FamilyAuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('family.password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('family.password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('family.password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('family.password.update');
    
    // Logout
    Route::post('/logout', [FamilyAuthController::class, 'logout'])->name('family.logout');
    
    // Protected family routes
    Route::middleware('auth:family')->group(function () {
        Route::get('/dashboard', [FamilyDashboardController::class, 'index'])->name('family.dashboard');
        
        // Family Needs Management
        Route::resource('needs', FamilyNeedController::class);
        
        // Family Profile Management
        Route::get('/profile', [FamilyProfileController::class, 'edit'])->name('family.profile.edit');
        Route::patch('/profile', [FamilyProfileController::class, 'update'])->name('family.profile.update');
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Public admin routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Family Management
        Route::resource('families', FamilyController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        
        // Need Management
        Route::resource('needs', AdminNeedController::class)->only(['index', 'show', 'destroy']);
        
        // Additional Admin Features
        Route::get('/export/{type}', [AdminDashboardController::class, 'export'])->name('admin.export');
    });
});

// Need Types Management (Admin only)
Route::middleware('auth:admin')->group(function () {
    Route::resource('need-types', NeedTypeController::class);
});

// Helper Management
Route::middleware('auth:admin')->get('/helpers', [AdminHelperController::class, 'index'])->name('admin.helpers.index');
Route::middleware('auth:family')->get('/my-helpers', [FamilyHelperController::class, 'index'])->name('family.helpers.index');

// Demo Data Routes
Route::get('/demo', [DemoController::class, 'index'])->name('demo');

// Fallback Route
Route::fallback(function () {
    return view('errors.404');
});