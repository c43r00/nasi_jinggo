<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // 1. Nama tabel di database
    protected $table = 'transaksi';

    // 2. Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama_pembeli',
        'varian',
        'jumlah',
        'harga',
        'total',
        'metode_pembayaran',
    ];

    // 3. TAMBAHKAN INI jika di migration Anda tidak ada $table->timestamps();
    // public $timestamps = false; 
}