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
    public function index()
    {
        $user = Auth::user();
        
        // Dashboard berbeda berdasarkan role
        switch ($user->role) {
            case 'pemilik':
                return $this->pemilikDashboard();
            case 'staff_dapur':
                return $this->staffDapurDashboard();
            case 'kasir':
                return $this->kasirDashboard();
            default:
                return redirect()->route('login');
        }
    }

    /**
     * Dashboard untuk Pemilik
     */
    private function pemilikDashboard()
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
            'recentTransactions'
        ));
    }

    /**
     * Dashboard untuk Staff Dapur
     */
    private function staffDapurDashboard()
    {
        $user = Auth::user();
        
        // Produksi Hari Ini oleh user ini
        $myProductionToday = Production::where('user_id', $user->id)
            ->whereDate('production_date', today())
            ->sum('quantity_produced');
        
        // Total Produksi Hari Ini (semua staff)
        $totalProductionToday = Production::whereDate('production_date', today())
            ->sum('quantity_produced');
        
        // Bahan Baku Tersedia
        $availableIngredients = Ingredient::where('stock_quantity', '>', 0)->count();
        
        // Bahan Baku Menipis
        $lowStockIngredients = Ingredient::whereColumn('stock_quantity', '<=', 'minimum_stock')->count();
        
        // Bahan Baku Habis
        $outOfStockIngredients = Ingredient::where('stock_quantity', '<=', 0)->count();
        
        // Recent Productions (5 terakhir dari user ini)
        $recentProductions = Production::with('product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Daftar Bahan Baku Menipis
        $lowStockList = Ingredient::with('category')
            ->whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->orderBy('stock_quantity', 'asc')
            ->get();
        
        // Produk yang bisa diproduksi
        $products = Product::where('is_active', true)->get();
        
        return view('dashboard.staff-dapur', compact(
            'myProductionToday',
            'totalProductionToday',
            'availableIngredients',
            'lowStockIngredients',
            'outOfStockIngredients',
            'recentProductions',
            'lowStockList',
            'products'
        ));
    }

    /**
     * Dashboard untuk Kasir
     */
    private function kasirDashboard()
    {
        $user = Auth::user();
        
        // Penjualan Hari Ini oleh kasir ini
        $mySalesToday = Sale::where('user_id', $user->id)
            ->whereDate('sale_date', today())
            ->sum('total_amount');
        
        // Total Transaksi Hari Ini oleh kasir ini
        $myTransactionsToday = Sale::where('user_id', $user->id)
            ->whereDate('sale_date', today())
            ->count();
        
        // Total Penjualan Hari Ini (semua kasir)
        $totalSalesToday = Sale::whereDate('sale_date', today())->sum('total_amount');
        
        // Total Transaksi Hari Ini (semua kasir)
        $totalTransactionsToday = Sale::whereDate('sale_date', today())->count();
        
        // Produk Tersedia
        $availableProducts = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->count();
        
        // Produk Habis
        $outOfStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 0)
            ->count();
        
        // Recent Sales (5 transaksi terakhir dari kasir ini)
        $recentSales = Sale::with('saleItems.product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Produk untuk POS
        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get();
        
        // Payment Methods
        $paymentMethods = ['cash', 'transfer', 'qris', 'debit'];
        
        return view('dashboard.kasir', compact(
            'mySalesToday',
            'myTransactionsToday',
            'totalSalesToday',
            'totalTransactionsToday',
            'availableProducts',
            'outOfStockProducts',
            'recentSales',
            'products',
            'paymentMethods'
        ));
    }
}