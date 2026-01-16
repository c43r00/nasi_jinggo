<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class KasirController extends Controller
{
    public function transaksi()
    {
        return view('kasir.transaksi');
    }

    public function simpanTransaksi(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pembeli'      => 'required|string',
            'varian'            => 'required|string',
            'jumlah'            => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string',
        ]);

        // Daftar harga menu
        $hargaMenu = [
            'Nasi Jinggo Ayam'   => 8000,
            'Nasi Jinggo Telur'  => 6000,
            'Nasi Jinggo Udang'  => 10000,
            'Nasi Jinggo Ikan'   => 9000,
            'Nasi Jinggo Tempe'  => 5000,
            'Nasi Jinggo Tahu'   => 5000,
            'Nasi Jinggo Combo'  => 12000,
        ];

        // Ambil harga sesuai menu
        $harga = $hargaMenu[$request->varian];
        $total = $harga * $request->jumlah;

        // SIMPAN ke database (INI YANG TADI KURANG)
        $transaksi = Transaksi::create([
            'nama_pembeli'       => $request->nama_pembeli,
            'varian'             => $request->varian,
            'jumlah'             => $request->jumlah,
            'harga'              => $harga,
            'total'              => $total,
            'metode_pembayaran'  => $request->metode_pembayaran,
        ]);

        return redirect()->route('kasir.rincian', $transaksi->id);
    }

    public function rincian($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('kasir.rincian', compact('transaksi'));
    }
}
