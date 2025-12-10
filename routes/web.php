<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Estimates
    Route::resource('estimates', EstimationController::class)->except(['edit', 'update']);
    
    // History
    Route::get('history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('history/{estimate}', [HistoryController::class, 'show'])->name('history.show');

    // Main Settings
    Route::get('settings', [Settings\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [Settings\SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/reset', [Settings\SettingsController::class, 'reset'])->name('settings.reset');

    // Profile & Account Settings (existing)
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

require __DIR__.'/auth.php';
