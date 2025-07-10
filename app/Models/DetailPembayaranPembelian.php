<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaranPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembayaran_pembelian';

    protected $fillable = [
        'transaksi_pembelian_id',
        'metode_pembayaran',
        'jumlah_pembayaran',
        'tanggal_pembayaran', // Anda mungkin ingin menambahkan ini jika setiap pembayaran punya tanggal sendiri
        'keterangan',
    ];

    public function transaksiPembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'transaksi_pembelian_id');
    }
}
