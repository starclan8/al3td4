<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API V1 Routes
Route::prefix('v1')->group(function () {
    
    // Public API endpoints
    Route::get('/calendar', function () {
        return response()->json(['message' => 'Calendar API endpoint']);
    });

    // Protected API endpoints (require Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        
        // User info
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        
        // Needs API
        Route::get('/needs', function () {
            return response()->json(['message' => 'Needs API endpoint']);
        });
        
        // Admin API endpoints
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/users', function () {
                return response()->json(['message' => 'Admin users API']);
            });
        });
    });
});

// Sanctum token issuance (for mobile login)
Route::post('/sanctum/token', function (Request $request) {
    return response()->json(['message' => 'Token endpoint - implement authentication']);
});