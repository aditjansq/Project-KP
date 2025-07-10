<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'mobil_id',
        'pembeli_id',
        'metode_pembayaran',
        'total_harga',
        'harga_negosiasi',
        'tanggal_transaksi',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_transaksi' => 'date', // Pastikan ini ada
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }

    public function kreditDetail()
    {
        return $this->hasOne(TransaksiKreditDetail::class);
    }

    public function pembayaranDetails()
    {
        return $this->hasMany(TransaksiPenjualanPembayaranDetail::class, 'transaksi_id');
    }
}
