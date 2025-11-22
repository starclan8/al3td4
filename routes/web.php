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

// ... rest of your routesRoute::get('/', function () {
   Route::get('/', function () {
    return view('welcome'); // Landing page
})->name('home');

Route::get('/contact', function () {
    return view('contact'); // Contact page mentioned in emails
})->name('contact');

// Calendar Routes (Public Community Calendar)
Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/{need}', [App\Http\Controllers\CalendarController::class, 'show'])->name('calendar.show');

// Helper Sign-Up Routes (For signing up to help with a need)
Route::get('/help/{need}/create', [App\Http\Controllers\HelpController::class, 'create'])->name('help.create');
Route::post('/help/{need}', [App\Http\Controllers\HelpController::class, 'store'])->name('help.store');

// Family Authentication and Management Routes
Route::prefix('family')->group(function () {
    // Public family routes
    Route::get('/login', [App\Http\Controllers\Family\AuthController::class, 'showLoginForm'])->name('family.login');
    Route::post('/login', [App\Http\Controllers\Family\AuthController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Family\AuthController::class, 'showRegisterForm'])->name('family.register');
    Route::post('/register', [App\Http\Controllers\Family\AuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/password/request', [App\Http\Controllers\Family\ForgotPasswordController::class, 'showLinkRequestForm'])->name('family.password.request');
    Route::post('/password/email', [App\Http\Controllers\Family\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('family.password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Family\ResetPasswordController::class, 'showResetForm'])->name('family.password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Family\ResetPasswordController::class, 'reset'])->name('family.password.update');
    
    // Logout (can be public but typically under auth)
    Route::post('/logout', [App\Http\Controllers\Family\AuthController::class, 'logout'])->name('family.logout');
    
    // Protected family routes
    Route::middleware('auth:family')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Family\DashboardController::class, 'index'])->name('family.dashboard');
        
        // Family Needs Management
        Route::resource('needs', App\Http\Controllers\Family\NeedController::class);
        
        // Family Profile Management
        Route::get('/profile', [App\Http\Controllers\Family\ProfileController::class, 'edit'])->name('family.profile.edit');
        Route::patch('/profile', [App\Http\Controllers\Family\ProfileController::class, 'update'])->name('family.profile.update');
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Public admin routes
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');
    
    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Family Management
        Route::resource('families', App\Http\Controllers\Admin\FamilyController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        
        // Need Management
        Route::resource('needs', App\Http\Controllers\Admin\NeedController::class)->only(['index', 'show', 'destroy']);
        
        // Additional Admin Features (e.g., exports from controller methods)
        Route::get('/export/{type}', [App\Http\Controllers\Admin\DashboardController::class, 'export'])->name('admin.export');
    });
});

// Additional Routes (Inferred from models and features)
// Need Types (Admin or Public? Assuming Admin for management)
Route::middleware('auth:admin')->group(function () {
    Route::resource('need-types', App\Http\Controllers\Admin\NeedTypeController::class);
});

// Helper Management (Possibly admin or family views)
Route::middleware('auth:admin')->get('/helpers', [App\Http\Controllers\Admin\HelperController::class, 'index'])->name('admin.helpers.index');
Route::middleware('auth:family')->get('/my-helpers', [App\Http\Controllers\Family\HelperController::class, 'index'])->name('family.helpers.index');

// Demo Data Routes (For testing, if needed)
Route::get('/demo', [App\Http\Controllers\DemoController::class, 'index'])->name('demo'); // If demo mode is accessible

// Fallback Route (Optional)
Route::fallback(function () {
    return view('errors.404');
});