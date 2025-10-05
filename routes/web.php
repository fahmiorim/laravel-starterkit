<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BloodRequestController;
use App\Http\Controllers\Admin\BloodStockController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DonorController;
use App\Http\Controllers\Admin\DonorHistoryController;
use App\Http\Controllers\Admin\DonorCardController;
use App\Http\Controllers\Admin\DonationScheduleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;

// Public Controllers
use App\Http\Controllers\JadwalDonorController;
use App\Http\Controllers\PublicDonorController;
use App\Http\Controllers\UserDashboardController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');
// Public Donor Schedule
Route::get('/jadwal-donor', [JadwalDonorController::class, 'index'])->name('jadwal-donor.index');
Route::get('/jadwal-donor/{schedule}', [JadwalDonorController::class, 'show'])->name('jadwal-donor.show');

// Public Donor Profile
Route::get('/donors/{donor}', [PublicDonorController::class, 'show'])->name('donors.show');

// Public Donor Card Verification
Route::get('donor-cards/verify', [\App\Http\Controllers\Admin\DonorCardController::class, 'showVerificationForm'])
    ->name('donor-cards.verify-form');
    
Route::post('donor-cards/verify', [\App\Http\Controllers\Admin\DonorCardController::class, 'verifyCard'])
    ->name('donor-cards.verify-card');
    
Route::get('donor-cards/{cardNumber}/verify', [\App\Http\Controllers\Admin\DonorCardController::class, 'publicShow'])
    ->name('donor-cards.verify');

// Authentication Routes
require __DIR__.'/auth.php';

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', UserDashboardController::class)->name('dashboard');
    
    // User Profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        
        // Donor Management
        Route::resource('donors', DonorController::class);
        Route::resource('donor-histories', DonorHistoryController::class)->except(['show']);
        Route::get('donors/{donor}/kta', [DonorController::class, 'generateKta'])->name('donors.kta');
        
        // Donor Cards
        Route::resource('donor-cards', DonorCardController::class)->except(['show']);
        Route::get('donor-cards/{donorCard}/download-pdf', [DonorCardController::class, 'downloadPdf'])
            ->name('donor-cards.download-pdf');
        
        // Blood Management
        Route::resource('blood-stocks', BloodStockController::class)->only(['index', 'update']);
        Route::resource('blood-requests', BloodRequestController::class);
        
        // Donation Schedules
        Route::resource('schedules', DonationScheduleController::class);
        Route::put('schedules/{schedule}/publish', [DonationScheduleController::class, 'publish'])
            ->name('schedules.publish');
        Route::put('schedules/{schedule}/cancel', [DonationScheduleController::class, 'cancel'])
            ->name('schedules.cancel');
        
        // Reports
        Route::resource('reports', ReportController::class)->only(['index']);
        
        // Settings
        Route::get('/settings/{group?}', [SettingsController::class, 'index'])
            ->name('settings.index')
            ->where('group', 'general|company|social');
        Route::match(['post', 'put'], '/settings', [SettingsController::class, 'update'])
            ->name('settings.update');
        Route::post('/settings/remove-image', [SettingsController::class, 'removeImage'])
            ->name('settings.remove-image');
        
        // Profile
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        
        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });
;

// Fallback Route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
