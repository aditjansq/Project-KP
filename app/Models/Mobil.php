<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika Anda menggunakan factory

class Mobil extends Model
{
    use HasFactory; // Gunakan ini jika Anda ingin memanfaatkan model factories

    // Menentukan kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'kode_mobil',
        'jenis_mobil', // <-- Kolom baru 'jenis_mobil' ditambahkan di sini
        'tipe_mobil',
        'merek_mobil',
        'tahun_pembuatan',
        'warna_mobil',
        'harga_mobil',
        'bahan_bakar',
        'transmisi', // Tambahkan 'transmisi' di sini
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
     * Relasi banyak ke banyak dengan Pelanggan.
     * Banyak mobil dapat dimiliki oleh banyak pelanggan melalui tabel pivot 'mobil_pelanggan'.
     */
    public function pelanggan()
    {
        return $this->belongsToMany(Pelanggan::class, 'mobil_pelanggan');
    }
}
