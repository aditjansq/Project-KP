<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjual extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan (optional jika namanya sudah sesuai konvensi)
    protected $table = 'penjuals';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'kode_penjual',
        'nama',
        'tanggal_lahir',
        'pekerjaan',
        'alamat',
        'no_telepon',
        'ktp_pasangan',   // Tambahkan baris ini
        'kartu_keluarga', // Tambahkan baris ini
        'slip_gaji',      // Tambahkan baris ini
    ];
}
