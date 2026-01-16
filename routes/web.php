<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\IngredientPurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KasirController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| LOGIN & GUEST ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| PREVIEW HALAMAN KASIR (TANPA LOGIN) - Sesuaikan jika perlu middleware
|--------------------------------------------------------------------------
*/
Route::get('/kasir/transaksi', [KasirController::class, 'transaksi']);
Route::post('/kasir/simpan', [KasirController::class, 'simpanTransaksi']);
Route::get('/kasir/rincian/{id}', [KasirController::class, 'rincian'])->name('kasir.rincian');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (SEMUA YANG BUTUH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Logout & Dashboard
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role: PEMILIK
    Route::middleware('role:pemilik')->prefix('pemilik')->group(function () {
        // Employee Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // View (Read-only)
        Route::get('productions', [ProductionController::class, 'index'])->name('pemilik.productions.index');
        Route::get('productions/{production}', [ProductionController::class, 'show'])->name('pemilik.productions.show');
        Route::get('sales', [SaleController::class, 'index'])->name('pemilik.sales.index');
        Route::get('sales/{sale}', [SaleController::class, 'show'])->name('pemilik.sales.show');
        Route::get('ingredient-purchases', [IngredientPurchaseController::class, 'index'])->name('pemilik.ingredient-purchases.index');
        Route::get('ingredient-purchases/{ingredientPurchase}', [IngredientPurchaseController::class, 'show'])->name('pemilik.ingredient-purchases.show');
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('production', [ReportController::class, 'productionReport'])->name('production');
            Route::get('stock', [ReportController::class, 'stockReport'])->name('stock');
            Route::get('profit-loss', [ReportController::class, 'profitLossReport'])->name('profit-loss');
        });
    });

    // Role: STAFF DAPUR & PEMILIK
    Route::middleware('role:staff_dapur,pemilik')->prefix('dapur')->group(function () {
        Route::resource('productions', ProductionController::class);
        Route::get('productions-quick', [ProductionController::class, 'quickCreate'])->name('productions.quick-create');
    });

    // Role: KASIR & PEMILIK (POS LOGIN)
    Route::middleware('role:kasir,pemilik')->prefix('kasir')->group(function () {
        Route::resource('sales', SaleController::class);
        Route::get('pos', [SaleController::class, 'pos'])->name('pos.index');
        Route::post('pos/checkout', [SaleController::class, 'checkout'])->name('pos.checkout');
        Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    });

    // Profile Settings
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::patch('profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::patch('profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
});