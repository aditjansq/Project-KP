<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;    // Asumsikan model User ada di App\Models
use App\Models\Mobil;   // Asumsikan model Mobil ada di App\Models
use App\Models\Transaksi; // Asumsikan model Transaksi ada di App\Models

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard aplikasi untuk peran Admin.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Mengambil data statistik
        $totalUsers = User::count();
        $totalMobil = Mobil::count();

        // Menggunakan logika yang Anda berikan:
        // Hitung total transaksi pembeli (transaksi yang memiliki pembeli_id)
        // $totalTransaksiPembeli = Transaksi::whereNotNull('pembeli_id')->count();

        // Hitung total transaksi penjual (transaksi yang memiliki penjual_id)
        // $totalTransaksiPenjual = Transaksi::whereNotNull('penjual_id')->count();

        // Mengirim data ke tampilan 'roles.admin'
        return view('roles.admin', compact('totalUsers', 'totalMobil'));
    }
}
