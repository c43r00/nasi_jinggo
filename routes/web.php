<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\IngredientPurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\KasirController;



Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard - Redirect berdasarkan role
    Route::get('/dashboard', function() {
        $user = auth()->user();
        
        if ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        } elseif ($user->role === 'staff_dapur') {
            return redirect()->route('dapur.index');
        } elseif ($user->role === 'kasir') {
            return redirect()->route('kasir.index');
        }
        
        return redirect()->route('login');
    })->name('dashboard');

    // Role: PEMILIK
    Route::middleware('role:pemilik')->prefix('pemilik')->group(function () {
        // Dashboard Pemilik
        Route::get('/dashboard', [DashboardController::class, 'pemilikDashboard'])->name('pemilik.dashboard');
        
        // Employee Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users-export-pdf', [UserController::class, 'exportPdf'])->name('users.export-pdf');
        
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

    // Role: STAFF DAPUR
    Route::middleware('role:staff_dapur,pemilik')->prefix('dapur')->name('dapur.')->group(function () {
        Route::get('/', [DapurController::class, 'index'])->name('index');
        Route::post('/store', [DapurController::class, 'store'])->name('store');
        Route::get('/low-stock', [DapurController::class, 'getLowStock'])->name('low-stock');
        Route::get('/export-pdf', [DapurController::class, 'exportPDF'])->name('export-pdf');
        
        Route::resource('productions', ProductionController::class);
        Route::get('productions-quick', [ProductionController::class, 'quickCreate'])->name('productions.quick-create');
    });

    // Role: KASIR
    Route::middleware('role:kasir,pemilik')->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/', [KasirController::class, 'index'])->name('index');
        Route::get('/transaksi', [KasirController::class, 'transaksi'])->name('transaksi');
        Route::post('/simpan', [KasirController::class, 'simpanTransaksi'])->name('simpan');
        Route::get('/rincian/{id}', [KasirController::class, 'rincian'])->name('rincian');
        
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