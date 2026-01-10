<?php

namespace App\Http\Controllers;

// 1. Pastikan semua Model ini sudah di-import
use App\Models\Production;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\ProductionIngredient;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        $query = Production::with(['product', 'user']);
        
        if (!$user->isPemilik()) {
            $query->where('user_id', $user->id);
        }
        
        $productions = $query->orderBy('production_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalProduction = $query->sum('quantity_produced');
        $todayProduction = Production::whereDate('production_date', today())->sum('quantity_produced');
        
        return view('productions.index', compact('productions', 'totalProduction', 'todayProduction'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_produced' => 'required|integer|min:1',
            'production_date' => 'required|date',
            'notes' => 'nullable|string',
            'auto_deduct' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            
            $production = Production::create([
                'product_id' => $request->product_id,
                'quantity_produced' => $request->quantity_produced,
                'production_date' => $request->production_date,
                'user_id' => Auth::id(),
                'notes' => $request->notes,
            ]);

            // Logika potong stok bahan baku otomatis
            if ($request->has('auto_deduct') && $request->auto_deduct) {
                $recipes = $product->recipes()->with('ingredient')->get();
                
                foreach ($recipes as $recipe) {
                    $quantityNeeded = $recipe->quantity_needed * $request->quantity_produced;
                    $ingredient = $recipe->ingredient;
                    
                    if ($ingredient->stock_quantity < $quantityNeeded) {
                        DB::rollBack();
                        return back()->withInput()->withErrors([
                            'quantity_produced' => "Stok {$ingredient->name} tidak cukup!"
                        ]);
                    }
                    
                    $ingredient->stock_quantity -= $quantityNeeded;
                    $ingredient->save();
                    
                    ProductionIngredient::create([
                        'production_id' => $production->id,
                        'ingredient_id' => $recipe->ingredient_id,
                        'quantity_used' => $quantityNeeded,
                    ]);
                }
            }
            
            $product->stock_quantity += $request->quantity_produced;
            $product->save();
            
            DB::commit();
            return redirect()->route('productions.index')->with('success', 'Produksi berhasil dicatat!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy(Production $production)
    {
        if (!Auth::user()->isPemilik()) {
            abort(403, 'Hanya Pemilik yang boleh menghapus data.');
        }
        
        DB::beginTransaction();
        try {
            $production->load(['product', 'productionIngredients.ingredient']);
            
            $product = $production->product;
            $product->stock_quantity -= $production->quantity_produced;
            $product->save();
            
            foreach ($production->productionIngredients as $prodIngredient) {
                $ingredient = $prodIngredient->ingredient;
                $ingredient->stock_quantity += $prodIngredient->quantity_used;
                $ingredient->save();
            }
            
            $production->delete();
            DB::commit();
            
            return redirect()->route('productions.index')->with('success', 'Data produksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}