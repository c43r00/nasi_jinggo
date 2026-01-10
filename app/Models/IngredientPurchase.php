<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientPurchase extends Model
{
 use HasFactory;

    protected $fillable = [
        'purchase_code',
        'ingredient_id',
        'quantity',
        'price_per_unit',
        'total_price',
        'purchase_date',
        'supplier_name',
        'expired_date',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
        'purchase_date' => 'date',
        'expired_date' => 'date',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Auto generate purchase code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->purchase_code)) {
                $model->purchase_code = 'PB-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}


