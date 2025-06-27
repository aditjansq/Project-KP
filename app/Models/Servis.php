<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobil_id',          // kolom yang menghubungkan dengan tabel mobil
        'kode_servis',       // kolom kode servis yang di-generate otomatis
        'tanggal_servis',    // kolom tanggal servis
        'metode_pembayaran', // kolom metode pembayaran
        'total_harga',       // kolom total harga yang dihitung berdasarkan item
        'status',
    ];

    /**
     * Relasi antara Servis dan Mobil (many to one)
     */
    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id');  // Menghubungkan dengan model Mobil
    }

    /**
     * Relasi antara Servis dan Items (one to many)
     * Setiap servis bisa memiliki banyak item
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'servis_id');  // Menghubungkan dengan model Item
    }
}
