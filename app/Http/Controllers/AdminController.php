<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mobil;
use App\Models\TransaksiPembelian; // Import model TransaksiPembelian
use App\Models\TransaksiPenjualan; // Import model TransaksiPenjualan

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

        // Menghitung total transaksi pembelian
        $totalTransaksiPembelian = TransaksiPembelian::count();

        // Menghitung total transaksi penjualan
        $totalTransaksiPenjualan = TransaksiPenjualan::count();

        // Menghitung total mobil berdasarkan ketersediaan
        $totalMobilTerjual = Mobil::where('ketersediaan', 'terjual')->count();
        $totalMobilTersedia = Mobil::whereIn('ketersediaan', ['ada', 'servis'])->count(); // Diperbarui untuk menyertakan 'servis'
        $totalMobilServis = Mobil::where('ketersediaan', 'servis')->count();


        // Mengirim data ke tampilan 'roles.admin'
        return view('roles.admin', compact(
            'totalUsers',
            'totalMobil',
            'totalTransaksiPembelian',
            'totalTransaksiPenjualan',
            'totalMobilTerjual', // Variabel baru
            'totalMobilTersedia', // Variabel baru
            'totalMobilServis' // Variabel baru
        ));
    }
}
