<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DapurController extends Controller
{
    public function index()
    {
        // Ambil semua bahan dengan relasi ke purchases dan category
        $ingredients = Ingredient::with(['purchases', 'category'])->get();
        
        // Hitung statistik
        $stockMenipis = $ingredients->filter(function($item) {
            return $item->stock_quantity <= $item->minimum_stock;
        })->count();
        
        $normal = $ingredients->filter(function($item) {
            return $item->stock_quantity > $item->minimum_stock;
        })->count();
        
        // Group bahan berdasarkan status (TANPA expired_dekat)
        $groupedIngredients = $ingredients->map(function($ingredient) {
            $latestPurchase = $ingredient->purchases()
                ->orderBy('purchase_date', 'desc')
                ->first();
            
            $status = 'normal';
            if ($ingredient->stock_quantity <= $ingredient->minimum_stock) {
                $status = 'stok_menipis';
            }
            
            return [
                'ingredient' => $ingredient,
                'latest_purchase' => $latestPurchase,
                'status' => $status
            ];
        })->groupBy('status');
        
        return view('dapur.index', compact('groupedIngredients', 'stockMenipis', 'normal'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'purchase_date' => 'required|date',
            'ingredient_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'expired_date' => 'nullable|date|after:purchase_date',
        ], [
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'purchase_date.required' => 'Tanggal pembelian harus diisi',
            'ingredient_name.required' => 'Nama bahan harus diisi',
            'quantity.required' => 'Jumlah harus diisi',
            'quantity.min' => 'Jumlah tidak boleh negatif',
            'price_per_unit.required' => 'Harga per unit harus diisi',
            'price_per_unit.min' => 'Harga tidak boleh negatif',
            'supplier_name.required' => 'Nama supplier harus diisi',
            'expired_date.after' => 'Tanggal expired harus setelah tanggal pembelian',
        ]);
        
        DB::beginTransaction();
        try {
            $ingredient = Ingredient::where('name', $request->ingredient_name)->first();
            
            if (!$ingredient) {
                $ingredient = Ingredient::create([
                    'category_id' => $request->category_id,
                    'name' => $request->ingredient_name,
                    'unit' => 'kg',
                    'stock_quantity' => 0,
                    'minimum_stock' => 5,
                    'price_per_unit' => $request->price_per_unit,
                    'supplier_name' => $request->supplier_name,
                ]);
            }
            
            $purchase = IngredientPurchase::create([
                'ingredient_id' => $ingredient->id,
                'purchase_date' => $request->purchase_date,
                'quantity' => $request->quantity,
                'price_per_unit' => $request->price_per_unit,
                'total_price' => $request->quantity * $request->price_per_unit,
                'supplier_name' => $request->supplier_name,
                'expired_date' => $request->expired_date,
                'user_id' => auth()->id() ?? 1,
            ]);
            
            $ingredient->increment('stock_quantity', $request->quantity);
            
            DB::commit();
            
            return redirect()->route('dapur.index')
                ->with('success', 'Stok bahan berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function getLowStock()
    {
        $ingredients = Ingredient::where('stock_quantity', '<=', DB::raw('minimum_stock'))
            ->with('purchases')
            ->get();
            
        return response()->json($ingredients);
    }

    public function exportPDF()
    {
        // Ambil semua bahan dengan relasi
        $ingredients = Ingredient::with(['purchases', 'category'])->get();
    
        // Hitung statistik
        $stockMenipis = $ingredients->filter(function($item) {
            return $item->stock_quantity <= $item->minimum_stock;
        })->count();
    
        $normal = $ingredients->filter(function($item) {
            return $item->stock_quantity > $item->minimum_stock;
        })->count();
    
        // Group bahan berdasarkan status
        $groupedIngredients = $ingredients->map(function($ingredient) {
            $latestPurchase = $ingredient->purchases()
                ->orderBy('purchase_date', 'desc')
                ->first();
            
            $status = 'normal';
            if ($ingredient->stock_quantity <= $ingredient->minimum_stock) {
                $status = 'stok_menipis';
            }
            
            return [
                'ingredient' => $ingredient,
                'latest_purchase' => $latestPurchase,
                'status' => $status
            ];
        })->groupBy('status');

        // Array bulan Indonesia
        $bulanIndo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $tanggal = date('j');
        $bulan = $bulanIndo[date('n')];
        $tahun = date('Y');
    
        $data = [
            'title' => 'Laporan Stok Bahan Baku',
            'date' => "$tanggal $bulan $tahun",
            'stockMenipis' => $stockMenipis,
            'normal' => $normal,
            'groupedIngredients' => $groupedIngredients
        ];
    
        $pdf = Pdf::loadView('dapur.export-pdf', $data);
    
        return $pdf->download('Laporan-Stok-Bahan-Baku-' . date('Y-m-d') . '.pdf');
    }
}