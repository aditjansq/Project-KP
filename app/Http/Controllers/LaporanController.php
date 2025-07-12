<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPembelian;
use App\Models\TransaksiPenjualan;
use App\Models\Mobil;
use App\Models\Servis;
use App\Models\TransaksiKreditDetail; // Import model TransaksiKreditDetail
use PDF;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan mobil yang telah terjual.
     * Mengambil data transaksi penjualan dengan detail mobil, servis mobil, dan pembeli.
     *
     * @return \Illuminate\View\View
     */
    public function mobilTerjual()
    {
        // Eager load relasi 'mobil', 'pembeli', dan 'kreditDetail'.
        // Nama relasi diubah dari 'transaksiKreditDetail' menjadi 'kreditDetail' agar sesuai dengan model TransaksiPenjualan.
        $mobilTerjual = TransaksiPenjualan::with('mobil.servis', 'pembeli', 'kreditDetail')->get();

        return view('laporan.mobil_terjual', compact('mobilTerjual'));
    }

    /**
     * Mengunduh laporan mobil terjual sebagai PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportMobilTerjualPdf()
    {
        // Ambil data yang sama seperti di metode mobilTerjual, termasuk relasi kreditDetail.
        // Nama relasi diubah dari 'transaksiKreditDetail' menjadi 'kreditDetail' agar sesuai dengan model TransaksiPenjualan.
        $mobilTerjual = TransaksiPenjualan::with('mobil.servis', 'pembeli', 'kreditDetail')->get();

        $data = [
            'title' => 'Laporan Mobil Terjual',
            'date' => date('d/m/Y'),
            'mobilTerjual' => $mobilTerjual
        ];

        // Buat instance PDF dari view 'laporan.mobil_terjual_pdf'.
        $pdf = PDF::loadView('laporan.mobil_terjual_pdf', $data);

        // Unduh PDF dengan nama file yang spesifik.
        return $pdf->download('laporan-mobil-terjual_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Menampilkan laporan transaksi penjualan mobil.
     * Metode ini berfungsi serupa dengan mobilTerjual, namun bisa digunakan
     * jika Anda ingin memisahkan laporan berdasarkan terminologi.
     *
     * @return \Illuminate\View\View
     */
    public function transaksiPenjualan()
    {
        // Eager load relasi 'mobil' dan 'pembeli'.
        // Dari 'mobil', eager load juga relasi 'servis' untuk mendapatkan total biaya servis.
        $transaksiPenjualan = TransaksiPenjualan::with('mobil.servis', 'pembeli')->get();

        return view('laporan.transaksi_penjualan', compact('transaksiPenjualan'));
    }

    /**
     * Mengunduh laporan transaksi penjualan mobil sebagai PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportTransaksiPenjualanPdf()
    {
        // Ambil data yang sama seperti di metode transaksiPenjualan.
        $transaksiPenjualan = TransaksiPenjualan::with('mobil.servis', 'pembeli')->get();

        $data = [
            'title' => 'Laporan Transaksi Penjualan Mobil',
            'date' => date('d/m/Y'),
            'transaksiPenjualan' => $transaksiPenjualan
        ];

        // Buat instance PDF dari view 'laporan.transaksi_penjualan_pdf'.
        $pdf = PDF::loadView('laporan.transaksi_penjualan_pdf', $data);

        // Unduh PDF dengan nama file yang spesifik.
        return $pdf->download('laporan-transaksi-penjualan_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Menampilkan laporan mobil yang telah dibeli (stok masuk).
     *
     * @return \Illuminate\View\View
     */
    public function mobilDibeli()
    {
        // Mengambil semua data transaksi pembelian dengan eager loading relasi mobil, penjual,
        // serta memuat juga relasi servis dari setiap mobil.
        $mobilDibeli = TransaksiPembelian::with('mobil', 'penjual', 'mobil.servis')->get();

        return view('laporan.mobil_dibeli', compact('mobilDibeli'));
    }

    /**
     * Mengunduh laporan mobil dibeli sebagai PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportMobilDibeliPdf()
    {
        // Ambil data yang sama seperti di metode mobilDibeli, memuat relasi servis juga.
        $mobilDibeli = TransaksiPembelian::with('mobil', 'penjual', 'mobil.servis')->get();

        // Data yang akan dikirim ke view PDF.
        $data = [
            'title' => 'Laporan Mobil Dibeli (Stok Masuk)',
            'date' => date('d/m/Y'),
            'mobilDibeli' => $mobilDibeli
        ];

        // Buat instance PDF dari view 'laporan.mobil_dibeli_pdf'.
        $pdf = PDF::loadView('laporan.mobil_dibeli_pdf', $data);

        // Unduh PDF dengan nama file yang spesifik.
        return $pdf->download('laporan-mobil-dibeli_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Menampilkan laporan servis mobil.
     *
     * @return \Illuminate\View\View
     */
    public function mobilServis()
    {
        // Mengambil semua data servis dengan eager loading relasi mobil dan item servis.
        $servisMobil = Servis::with('mobil', 'items')->get();

        return view('laporan.mobil_servis', compact('servisMobil'));
    }

    /**
     * Mengunduh laporan servis mobil sebagai PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportMobilServisPdf()
    {
        // Ambil data servis yang sama seperti di metode mobilServis.
        $servisMobil = Servis::with('mobil', 'items')->get();

        // Data yang akan dikirim ke view PDF.
        $data = [
            'title' => 'Laporan Servis Mobil',
            'date' => date('d/m/Y'),
            'servisMobil' => $servisMobil
        ];

        // Buat instance PDF dari view 'laporan.mobil_servis_pdf'.
        $pdf = PDF::loadView('laporan.mobil_servis_pdf', $data);

        // Unduh PDF dengan nama file yang spesifik.
        return $pdf->download('laporan-servis-mobil_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Menampilkan laporan detail transaksi kredit.
     *
     * @return \Illuminate\View\View
     */
    public function transaksiKreditDetail()
    {
        // Eager load relasi transaksi_penjualan, mobil, pembeli, dan kreditDetail
        // Nama relasi diubah dari 'transaksiKreditDetail' menjadi 'kreditDetail' agar sesuai dengan model TransaksiPenjualan.
        $transaksiKreditDetail = TransaksiKreditDetail::with('transaksiPenjualan.mobil', 'transaksiPenjualan.pembeli')->get();

        return view('laporan.transaksi_kredit_detail', compact('transaksiKreditDetail'));
    }

    /**
     * Mengunduh laporan detail transaksi kredit sebagai PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportTransaksiKreditDetailPdf()
    {
        // Ambil data yang sama seperti di metode transaksiKreditDetail
        // Nama relasi diubah dari 'transaksiKreditDetail' menjadi 'kreditDetail' agar sesuai dengan model TransaksiPenjualan.
        $transaksiKreditDetail = TransaksiKreditDetail::with('transaksiPenjualan.mobil', 'transaksiPenjualan.pembeli')->get();

        $data = [
            'title' => 'Laporan Detail Transaksi Kredit',
            'date' => date('d/m/Y'),
            'transaksiKreditDetail' => $transaksiKreditDetail
        ];

        $pdf = PDF::loadView('laporan.transaksi_kredit_detail_pdf', $data);

        return $pdf->download('laporan-transaksi-kredit-detail_' . date('Ymd_His') . '.pdf');
    }
}
