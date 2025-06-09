<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ActivityLogController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
require __DIR__.'/auth.php';


// Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Users Management
        Route::resource('users', UserController::class);

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Roles Management
        Route::resource('roles', RoleController::class);
        
        // Permissions Management
        Route::resource('permissions', PermissionController::class);
        
        // Settings Management
        Route::get('/settings/{group?}', [SettingsController::class, 'index'])->name('settings.index')->where('group', 'general|company|social');
        Route::match(['post', 'put'], '/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/remove-image', [SettingsController::class, 'removeImage'])->name('settings.remove-image');
        
        // Activity Log
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');
        Route::resource('permissions', PermissionController::class);
        
        // Additional admin routes can be added here
    });
});

// Fallback Route - Must be at the end
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
