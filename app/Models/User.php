<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',           // ← Tambahkan ini
        'phone',          // ← Tambahkan ini
        'is_active',      // ← Tambahkan ini
        'created_by',     // ← Tambahkan ini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * User yang membuat akun ini (pemilik yang buat karyawan)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Users yang dibuat oleh user ini
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Sales/transaksi yang dibuat oleh user ini
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Productions yang dibuat oleh user ini
     */
    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    /**
     * Ingredient purchases yang dibuat oleh user ini
     */
    public function ingredientPurchases()
    {
        return $this->hasMany(IngredientPurchase::class);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Check if user is pemilik
     */
    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

    /**
     * Check if user is staff dapur
     */
    public function isStaffDapur(): bool
    {
        return $this->role === 'staff_dapur';
    }

    /**
     * Check if user is kasir
     */
    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    /**
     * Get role label in Indonesian
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'pemilik' => 'Pemilik',
            'staff_dapur' => 'Staff Dapur',
            'kasir' => 'Kasir',
            default => $this->role,
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get role badge color for UI
     */
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