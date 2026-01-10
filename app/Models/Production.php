<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_code',
        'product_id',
        'quantity_produced',
        'production_date',
        'user_id',
        'notes'
    ];

    protected $casts = [
        'production_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productionIngredients()
    {
        return $this->hasMany(ProductionIngredient::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'production_ingredients')
            ->withPivot('quantity_used')
            ->withTimestamps();
    }

    // Auto generate production code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->production_code)) {
                $model->production_code = 'PRD-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
