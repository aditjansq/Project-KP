<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller // Nama kelas diubah menjadi LaporanController
{
    /**
     * Menampilkan laporan mobil yang telah terjual.
     *
     * @return \Illuminate\View\View
     */
    public function mobilTerjual() // Nama metode tetap bagus dalam Bahasa Indonesia
    {
        // Logika untuk mengambil dan mengolah data mobil yang terjual
        // Contoh:
        // $dataMobilTerjual = \App\Models\Transaksi::where('jenis_transaksi', 'penjualan')->get();

        return view('laporan.mobil_terjual'); // Mengembalikan view untuk laporan mobil terjual
    }

    /**
     * Menampilkan laporan mobil yang telah dibeli (stok masuk).
     *
     * @return \Illuminate\View\View
     */
    public function mobilDibeli() // Nama metode tetap bagus dalam Bahasa Indonesia
    {
        // Logika untuk mengambil dan mengolah data mobil yang dibeli (stok masuk)
        // Contoh:
        // $dataMobilDibeli = \App\Models\PembelianMobil::all(); // Sesuaikan dengan model atau logika Anda

        return view('laporan.mobil_dibeli'); // Mengembalikan view untuk laporan mobil dibeli
    }

    // Anda bisa menambahkan metode lain untuk laporan di sini, misalnya:
    // public function laporanKeuangan() { ... }
    // public function laporanServis() { ... }
}
