<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\User; // Dihapus karena totalUsers tidak lagi digunakan di dashboard sales
use App\Models\Mobil;
use App\Models\TransaksiPembelian; // Import model TransaksiPembelian
use App\Models\TransaksiPenjualan; // Import model TransaksiPenjualan

class SalesController extends Controller
{
    /**
     * Tampilkan dashboard aplikasi untuk peran Sales.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Mengambil data statistik yang relevan untuk Sales
        // $totalUsers = User::count(); // Dihapus: tidak diperlukan di dashboard sales
        $totalMobil = Mobil::count(); // Dihapus: diganti dengan totalMobilTersedia yang lebih spesifik

        // Menghitung total transaksi pembelian
        $totalTransaksiPembelian = TransaksiPembelian::count();

        // Menghitung total transaksi penjualan
        $totalTransaksiPenjualan = TransaksiPenjualan::count();

        // Menghitung total mobil berdasarkan ketersediaan
        $totalMobilTerjual = Mobil::where('ketersediaan', 'terjual')->count();
        $totalMobilTersedia = Mobil::whereIn('ketersediaan', ['ada', 'servis'])->count(); // Diperbarui untuk menyertakan 'servis'
        // $totalMobilServis = Mobil::where('ketersediaan', 'servis')->count(); // Dihapus: tidak diperlukan di dashboard sales


        // Mengirim data ke tampilan 'roles.sales'
        return view('roles.sales', compact(
            'totalTransaksiPembelian',
            'totalTransaksiPenjualan',
            'totalMobilTerjual',
            'totalMobilTersedia'
        ));
    }
}
