<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = Sale::with(['user', 'saleItems.product']);
        
        // Filter by user if not pemilik
        if (!$user->isPemilik()) {
            $query->where('user_id', $user->id);
        }
        
        $sales = $query->orderBy('sale_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistics
        $totalSales = $query->sum('total_amount');
        $todaySales = Sale::whereDate('sale_date', today())->sum('total_amount');
        $todayTransactions = Sale::whereDate('sale_date', today())->count();
        
        return view('sales.index', compact('sales', 'totalSales', 'todaySales', 'todayTransactions'));
    }

    /**
     * Show POS interface
     */
    public function pos()
    {
        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get();
        
        $paymentMethods = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            'debit' => 'Kartu Debit',
        ];
        
        return view('sales.pos', compact('products', 'paymentMethods'));
    }

    /**
     * Process checkout from POS
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,qris,debit',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'items.required' => 'Keranjang belanja masih kosong',
            'items.min' => 'Minimal 1 item harus dibeli',
            'payment_method.required' => 'Metode pembayaran harus dipilih',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            
            // Validate stock availability first
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak mencukupi. Tersedia: {$product->stock_quantity}"
                    ], 422);
                }
                
                $totalAmount += $item['quantity'] * $item['price'];
            }
            
            // Create sale record
            $sale = Sale::create([
                'sale_date' => today(),
                'customer_name' => $request->customer_name,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'user_id' => Auth::id(),
                'notes' => $request->notes,
            ]);
            
            // Create sale items and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $item['quantity'] * $item['price'];
                
                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
                
                // Deduct product stock
                $product->decrement('stock_quantity', $item['quantity']);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'sale_id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'saleItems.product']);
        
        return view('sales.show', compact('sale'));
    }

    /**
     * Show receipt for printing
     */
    public function receipt(Sale $sale)
    {
        $sale->load(['user', 'saleItems.product']);
        
        return view('sales.receipt', compact('sale'));
    }

    /**
     * Remove the specified sale
     */
    public function destroy(Sale $sale)
    {
        // Only pemilik can delete
        if (!Auth::user()->isPemilik()) {
            abort(403, 'Unauthorized action.');
        }
        
        DB::beginTransaction();
        try {
            // Revert product stock
            foreach ($sale->saleItems as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
            
            $sale->delete();
            
            DB::commit();
            
            return redirect()->route('sales.index')->with('success', 'Transaksi berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}