<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    protected $fillable = [
        'kode_mobil', 'tipe_mobil', 'merek_mobil', 'tahun_pembuatan', 'warna_mobil',
        'harga_mobil', 'bahan_bakar', 'nomor_polisi', 'nomor_rangka',
        'nomor_mesin', 'nomor_bpkb', 'tanggal_masuk', 'status_mobil', 'stok'
    ];

    protected $dates = ['tanggal_masuk'];
}
