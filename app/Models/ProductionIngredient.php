<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionIngredient extends Model
{
use HasFactory;

    protected $fillable = [
        'production_id',
        'ingredient_id',
        'quantity_used'
    ];

    protected $casts = [
        'quantity_used' => 'decimal:2',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
