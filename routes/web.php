<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPasswordResetController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/chart-data', [DashboardController::class, 'getChartData'])->name('chart.data');
    
    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/api/category-suggestions', [TransactionController::class, 'suggestions'])->name('categories.suggestions');
    Route::get('/transactions/export', [ExportController::class, 'exportTransactions'])->name('transactions.export');
    Route::get('/statements/download/{period}', [ExportController::class, 'downloadStatement'])->name('statements.download');
    
    // Comparison
    Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison.index');
    Route::post('/api/compare', [ComparisonController::class, 'compare'])->name('comparison.compare');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    
    // Profile
    Route::put('/api/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/api/change-password', [ProfileController::class, 'changePassword'])->name('password.change');
    Route::delete('/api/account', [ProfileController::class, 'deleteAccount'])->name('account.delete');
    
    // Theme
    Route::post('/api/theme', [ThemeController::class, 'update'])->name('theme.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.attempt');

        Route::get('/forgot-password', [AdminPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [AdminPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [AdminPasswordResetController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [AdminPasswordResetController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
