<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientPurchaseController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes untuk PEMILIK
    Route::middleware('role:pemilik')->group(function () {
        // Employee Management
        Route::resource('users', UserController::class);
        
        // Master Data
        Route::resource('categories', CategoryController::class);
        Route::resource('ingredients', IngredientController::class);
        Route::resource('products', ProductController::class);
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/production', [ReportController::class, 'productionReport'])->name('reports.production');
        Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
        
        // Export Reports
        Route::get('/reports/sales/pdf', [ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
        Route::get('/reports/sales/excel', [ReportController::class, 'salesExcel'])->name('reports.sales.excel');
        Route::get('/reports/production/pdf', [ReportController::class, 'productionPdf'])->name('reports.production.pdf');
        Route::get('/reports/production/excel', [ReportController::class, 'productionExcel'])->name('reports.production.excel');
        Route::get('/reports/stock/pdf', [ReportController::class, 'stockPdf'])->name('reports.stock.pdf');
        
        // Chart Data API
        Route::get('/api/chart/sales', [ReportController::class, 'salesChartData'])->name('api.chart.sales');
        Route::get('/api/chart/production', [ReportController::class, 'productionChartData'])->name('api.chart.production');
    });
    
    // Routes untuk STAFF DAPUR
    Route::middleware('role:staff_dapur,pemilik')->group(function () {
        // Production Management
        Route::resource('productions', ProductionController::class);
        Route::get('/productions/create/quick', [ProductionController::class, 'quickCreate'])->name('productions.quick-create');
        
        // Ingredient Purchase
        Route::resource('ingredient-purchases', IngredientPurchaseController::class);
        
        // Stock Overview
        Route::get('/stock-overview', [IngredientController::class, 'stockOverview'])->name('stock.overview');
    });
    
    // Routes untuk KASIR
    Route::middleware('role:kasir,pemilik')->group(function () {
        // Sales/POS
        Route::resource('sales', SaleController::class);
        Route::get('/pos', [SaleController::class, 'pos'])->name('pos.index');
        Route::post('/pos/checkout', [SaleController::class, 'checkout'])->name('pos.checkout');
        Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    });
});