<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengganti atau menambahkan ini untuk mengimpor model TransaksiPembelian
use App\Models\TransaksiPembelian;
use App\Models\Servis; // Pastikan ini juga ada jika belum
use App\Models\Transaksi; // Ini diasumsikan adalah model Transaksi umum
use App\Models\TransaksiPenjualan; // <-- TAMBAHKAN INI

class Mobil extends Model
{
    use HasFactory;

    // Menentukan kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'kode_mobil',
        'jenis_mobil', // Kolom 'jenis_mobil'
        'tipe_mobil',
        'merek_mobil',
        'tahun_pembuatan',
        'warna_mobil',
        'harga_mobil',
        'bahan_bakar',
        'transmisi', // Kolom 'transmisi'
        'nomor_polisi',
        'nomor_rangka',
        'nomor_mesin',
        'nomor_bpkb',
        'tanggal_masuk',
        'status_mobil',
        'ketersediaan',
        'masa_berlaku_pajak'
    ];

    // Menentukan kolom yang diperlakukan sebagai tanggal oleh Eloquent
    protected $dates = ['tanggal_masuk', 'masa_berlaku_pajak'];

    /**
     * Relasi satu ke banyak dengan Servis.
     * Satu mobil dapat memiliki banyak record servis.
     */
    public function servis()
    {
        return $this->hasMany(Servis::class);
    }

    /**
     * Relasi satu ke banyak dengan TransaksiPembelian.
     * Satu mobil dapat memiliki banyak record transaksi pembelian.
     */
    public function transaksiPembelian()
    {
        return $this->hasMany(TransaksiPembelian::class, 'mobil_id'); // 'mobil_id' adalah foreign key di tabel transaksis_pembelian
    }

    /**
     * Relasi satu ke banyak dengan TransaksiPenjualan.
     * Satu mobil dapat memiliki banyak record transaksi penjualan.
     * Asumsi 'mobil_id' adalah foreign key di tabel 'transaksi_penjualans'.
     */
    public function transaksiPenjualan() // <-- TAMBAHKAN RELASI INI
    {
        return $this->hasMany(TransaksiPenjualan::class, 'mobil_id');
    }

    /**
     * Dapatkan transaksi-transaksi umum untuk mobil ini.
     * Jika Anda memiliki model Transaksi umum yang berbeda dari Pembelian/Penjualan, ini bisa tetap ada.
     */
    public function transaksis()
    {
        // Asumsi 'mobil_id' adalah foreign key di tabel 'transaksis'
        return $this->hasMany(Transaksi::class, 'mobil_id');
    }

    /**
     * Relasi banyak ke banyak dengan Pelanggan.
     * Banyak mobil dapat dimiliki oleh banyak pelanggan melalui tabel pivot 'mobil_pelanggan'.
     */
    public function pelanggan()
    {
        return $this->belongsToMany(Pelanggan::class, 'mobil_pelanggan');
    }
}
