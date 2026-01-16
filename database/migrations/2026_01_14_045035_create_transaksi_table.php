<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pembeli');
            $table->string('varian');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('total');
            $table->string('metode_pembayaran');
            $table->timestamps(); // ✅ HANYA SATU KALI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
