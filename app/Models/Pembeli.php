<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pembeli',
        'nama',
        'tanggal_lahir',
        'pekerjaan',
        'alamat',
        'no_telepon',
        'ktp_pasangan',
        'kartu_keluarga',
        'slip_gaji',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date', // <--- INI PENTING SEKALI!
    ];

    // Jika Anda punya custom primary key atau nama tabel yang berbeda, tambahkan di sini:
    // protected $primaryKey = 'id_pembeli';
    // protected $table = 'nama_tabel_pembeli_anda';
}
