<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Firejob Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for our Firejob platform. These routes
| are loaded by the RouteServiceProvider and assigned to the "web"
| middleware group, which includes session state and CSRF protection.
|
*/

// Public routes
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Firejob Platform',
        'version' => '1.0.0',
        'status' => 'active',
        'laravel_version' => app()->version(),
    ]);
})->name('home');

// Health check route (already configured in bootstrap/app.php)
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
    ]);
})->name('health.check');

// API routes will be added in future phases
Route::prefix('api/v1')->group(function () {
    Route::get('/status', function () {
        return response()->json([
            'api_status' => 'active',
            'version' => 'v1.0.0',
            'endpoints' => [
                'users' => 'Coming in Phase 2',
                'services' => 'Coming in Phase 4',
                'orders' => 'Coming in Phase 5',
            ],
        ]);
    });
});
