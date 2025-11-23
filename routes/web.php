<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\NeedController as AdminNeedController;
use App\Http\Controllers\Admin\NeedTypeController;
use App\Http\Controllers\Admin\HelperController as AdminHelperController;
use App\Http\Controllers\Family\AuthController as FamilyAuthController;
use App\Http\Controllers\Family\ForgotPasswordController;
use App\Http\Controllers\Family\ResetPasswordController;
use App\Http\Controllers\Family\DashboardController as FamilyDashboardController;
use App\Http\Controllers\Family\NeedController as FamilyNeedController;
use App\Http\Controllers\Family\ProfileController as FamilyProfileController;
use App\Http\Controllers\Family\HelperController as FamilyHelperController;

// ============================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================

// Landing and Contact Pages
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Public Calendar (Community Calendar)
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/{need}', [CalendarController::class, 'show'])->name('calendar.show');

// Demo Routes (if needed)
Route::get('/demo', [DemoController::class, 'index'])->name('demo');

// ============================================
// AUTHENTICATED USER ROUTES
// ============================================

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// HELPER SIGN-UP ROUTES
// ============================================

Route::middleware(['auth', 'permission:sign up as helper'])->group(function () {
    Route::get('/help/{need}/create', [HelpController::class, 'create'])->name('help.create');
    Route::post('/help/{need}', [HelpController::class, 'store'])->name('help.store');
});

// ============================================
// FAMILY PORTAL ROUTES
// ============================================

Route::prefix('family')->name('family.')->group(function () {
    // Public Family Routes (Login/Register)
    Route::get('/login', [FamilyAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [FamilyAuthController::class, 'login']);
    Route::get('/register', [FamilyAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [FamilyAuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    
    // Logout
    Route::post('/logout', [FamilyAuthController::class, 'logout'])->name('logout');
    
    // Protected Family Routes
    Route::middleware('auth:family')->group(function () {
        Route::get('/dashboard', [FamilyDashboardController::class, 'index'])->name('dashboard');
        
        // Family Needs Management
        Route::resource('needs', FamilyNeedController::class);
        
        // Family Profile Management
        Route::get('/profile', [FamilyProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [FamilyProfileController::class, 'update'])->name('profile.update');
        
        // My Helpers
        Route::get('/my-helpers', [FamilyHelperController::class, 'index'])->name('helpers.index');
    });
});

// ============================================
// ADMIN PORTAL ROUTES
// ============================================

Route::prefix('admin')->name('admin.')->group(function () {
    // Public Admin Routes (Login)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // User Management with Permissions
        Route::middleware('permission:view users')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
        });
        
        Route::middleware('permission:edit users')->group(function () {
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        });
        
        Route::middleware('permission:delete users')->group(function () {
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
        
        // Family Management
        Route::resource('families', FamilyController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        
        // Need Management
        Route::resource('needs', AdminNeedController::class)->only(['index', 'show', 'destroy']);
        
        // Need Types Management
        Route::resource('need-types', NeedTypeController::class);
        
        // Helper Management
        Route::get('/helpers', [AdminHelperController::class, 'index'])->name('helpers.index');
        
        // Export Data
        Route::get('/export/{type}', [DashboardController::class, 'export'])->name('export');
    });
});

// ============================================
// NEED MANAGEMENT ROUTES (Public/Authenticated)
// ============================================

Route::middleware(['auth', 'permission:view needs'])->group(function () {
    Route::get('/needs', [FamilyNeedController::class, 'index'])->name('needs.index');
    Route::get('/needs/{need}', [FamilyNeedController::class, 'show'])->name('needs.show');
});

Route::middleware(['auth', 'permission:create needs'])->group(function () {
    Route::get('/needs/create', [FamilyNeedController::class, 'create'])->name('needs.create');
    Route::post('/needs', [FamilyNeedController::class, 'store'])->name('needs.store');
});

Route::middleware(['auth', 'permission:edit own needs'])->group(function () {
    Route::get('/needs/{need}/edit', [FamilyNeedController::class, 'edit'])->name('needs.edit');
    Route::put('/needs/{need}', [FamilyNeedController::class, 'update'])->name('needs.update');
});

// ============================================
// AUTHENTICATION ROUTES
// ============================================

require __DIR__.'/auth.php';

// ============================================
// FALLBACK ROUTE
// ============================================

Route::fallback(function () {
    return view('errors.404');
});