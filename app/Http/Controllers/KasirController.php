<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Sale;
use App\Models\Product;

class KasirController extends Controller
{
    /**
     * Dashboard Kasir
     */
    public function index()
    {
        $user = Auth::user();
        
        // Penjualan Hari Ini oleh kasir ini
        $mySalesToday = Sale::where('user_id', $user->id)
            ->whereDate('sale_date', today())
            ->sum('total_amount') ?? 0;
        
        // Jika menggunakan tabel Transaksi
        $myTransactionsTodayFromTransaksi = Transaksi::whereDate('created_at', today())->sum('total') ?? 0;
        
        // Total gabungan
        $totalSalesToday = $mySalesToday + $myTransactionsTodayFromTransaksi;
        
        // Total Transaksi Hari Ini
        $myTransactionsToday = Sale::where('user_id', $user->id)
            ->whereDate('sale_date', today())
            ->count();
        
        $transactionCountFromTransaksi = Transaksi::whereDate('created_at', today())->count();
        $totalTransactionsToday = $myTransactionsToday + $transactionCountFromTransaksi;
        
        // Produk Tersedia
        $availableProducts = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->count();
        
        // Produk Habis
        $outOfStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 0)
            ->count();
        
        // Recent Sales dari tabel Sales
        $recentSales = Sale::with('saleItems.product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent Transactions dari tabel Transaksi
        $recentTransaksi = Transaksi::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Produk untuk POS
        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get();
        
        return view('kasir.index', compact(
            'totalSalesToday',
            'totalTransactionsToday',
            'availableProducts',
            'outOfStockProducts',
            'recentSales',
            'recentTransaksi',
            'products'
        ));
    }

    /**
     * Halaman Transaksi (POS)
     */
    public function transaksi()
    {
        return view('kasir.transaksi');
    }

    /**
     * Simpan Transaksi Baru
     */
    public function simpanTransaksi(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pembeli'      => 'required|string',
            'varian'            => 'required|string',
            'jumlah'            => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string',
        ]);

        // Daftar harga menu
        $hargaMenu = [
            'Nasi Jinggo Ayam'   => 8000,
            'Nasi Jinggo Telur'  => 6000,
            'Nasi Jinggo Udang'  => 10000,
            'Nasi Jinggo Ikan'   => 9000,
            'Nasi Jinggo Tempe'  => 5000,
            'Nasi Jinggo Tahu'   => 5000,
            'Nasi Jinggo Combo'  => 12000,
        ];

        // Ambil harga sesuai menu
        $harga = $hargaMenu[$request->varian];
        $total = $harga * $request->jumlah;

        // Simpan ke database
        $transaksi = Transaksi::create([
            'nama_pembeli'       => $request->nama_pembeli,
            'varian'             => $request->varian,
            'jumlah'             => $request->jumlah,
            'harga'              => $harga,
            'total'              => $total,
            'metode_pembayaran'  => $request->metode_pembayaran,
        ]);

        return redirect()->route('kasir.rincian', $transaksi->id)
            ->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Detail Transaksi
     */
    public function rincian($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('kasir.rincian', compact('transaksi'));
    }
}