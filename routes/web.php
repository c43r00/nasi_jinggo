<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientPurchaseController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DapurController;

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
    
    // Dashboard (All roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
  
    Route::middleware('role:pemilik')->prefix('pemilik')->group(function () {
        // Employee Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
   
    Route::middleware('role:staff_dapur,pemilik')->prefix('dapur')->group(function () {
        // Production Management
        Route::resource('productions', ProductionController::class);
        Route::get('productions-quick', [ProductionController::class, 'quickCreate'])->name('productions.quick-create');
    });
    

    Route::middleware('role:kasir,pemilik')->prefix('kasir')->group(function () {
        // Sales/POS
        Route::resource('sales', SaleController::class);
        Route::get('pos', [SaleController::class, 'pos'])->name('pos.index');
        Route::post('pos/checkout', [SaleController::class, 'checkout'])->name('pos.checkout');
        Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    });
    
    
    // Profile
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::patch('profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::patch('profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
});

   Route::prefix('dapur')->name('dapur.')->group(function () {
    Route::get('/', [DapurController::class, 'index'])->name('index');
    Route::post('/store', [DapurController::class, 'store'])->name('store');
    Route::get('/low-stock', [DapurController::class, 'getLowStock'])->name('low-stock');
    Route::get('/export-pdf', [DapurController::class, 'exportPDF'])->name('export-pdf');
});