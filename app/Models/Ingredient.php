<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
  
 use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'unit',
        'stock_quantity',
        'minimum_stock',
        'price_per_unit',
        'supplier_name'
    ];

    protected $casts = [
        'stock_quantity' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchases()
    {
        return $this->hasMany(IngredientPurchase::class);
    }

    public function productRecipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }

    public function productionIngredients()
    {
        return $this->hasMany(ProductionIngredient::class);
    }

    // Check if stock is below minimum
    public function isLowStock()
    {
        return $this->stock_quantity <= $this->minimum_stock;
    }

    // Get stock status
    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'habis';
        } elseif ($this->isLowStock()) {
            return 'menipis';
        } else {
            return 'aman';
        }
    }
}