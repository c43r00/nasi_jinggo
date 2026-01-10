<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
        'created_by',
    ];

  
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

   
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

   
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

   
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    
    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    
    public function ingredientPurchases()
    {
        return $this->hasMany(IngredientPurchase::class);
    }

    
    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

  
    public function isStaffDapur(): bool
    {
        return $this->role === 'staff_dapur';
    }

  
    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'pemilik' => 'Pemilik',
            'staff_dapur' => 'Staff Dapur',
            'kasir' => 'Kasir',
            default => $this->role,
        };
    }

  
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }


    public function getRoleBadgeColorAttribute(): string
    {
        return match($this->role) {
            'pemilik' => 'bg-blue-100 text-blue-800',
            'staff_dapur' => 'bg-green-100 text-green-800',
            'kasir' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}