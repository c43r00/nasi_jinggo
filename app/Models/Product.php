<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Get ingredients needed for this product
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_recipes')
            ->withPivot('quantity_needed')
            ->withTimestamps();
    }
}
