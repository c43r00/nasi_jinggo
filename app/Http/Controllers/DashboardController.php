<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Sale;
use App\Models\Production;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk Pemilik ONLY
     */
    public function pemilikDashboard()
    {
        // Total Penjualan Hari Ini
        $salesToday = Sale::whereDate('sale_date', today())->sum('total_amount');
        
        // Total Penjualan Bulan Ini
        $salesThisMonth = Sale::whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->sum('total_amount');
        
        // Total Transaksi Hari Ini
        $transactionsToday = Sale::whereDate('sale_date', today())->count();
        
        // Total Transaksi Bulan Ini
        $transactionsThisMonth = Sale::whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->count();
        
        // Total Produksi Hari Ini
        $productionToday = Production::whereDate('production_date', today())->sum('quantity_produced');
        
        // Total Produksi Bulan Ini
        $productionThisMonth = Production::whereMonth('production_date', now()->month)
            ->whereYear('production_date', now()->year)
            ->sum('quantity_produced');
        
        // Total Karyawan Aktif
        $activeEmployees = User::where('is_active', true)
            ->where('role', '!=', 'pemilik')
            ->count();
        
        // Stok Bahan Baku Menipis
        $lowStockIngredients = Ingredient::whereColumn('stock_quantity', '<=', 'minimum_stock')->count();
        
        // Produk Terlaris (Top 5)
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        // Penjualan 7 Hari Terakhir (untuk chart)
        $salesLastWeek = Sale::where('sale_date', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total_amount) as total'))
            ->orderBy('date')
            ->get();
        
        // Recent Transactions (5 terakhir)
        $recentTransactions = Sale::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Karyawan yang Bekerja Hari Ini
        $employeesWorkingToday = User::select('users.id', 'users.name', 'users.role', DB::raw('COUNT(sales.id) as transaction_count'))
            ->join('sales', 'users.id', '=', 'sales.user_id')
            ->whereDate('sales.sale_date', today())
            ->where('users.role', '!=', 'pemilik')
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderByDesc('transaction_count')
            ->get();
        
        return view('dashboard.pemilik', compact(
            'salesToday',
            'salesThisMonth',
            'transactionsToday',
            'transactionsThisMonth',
            'productionToday',
            'productionThisMonth',
            'activeEmployees',
            'lowStockIngredients',
            'topProducts',
            'salesLastWeek',
            'recentTransactions',
            'employeesWorkingToday'
        ));
    }
}