<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi Laravel (plural dari nama model)
    protected $table = 'transaksi_pembelians';

    // Menentukan kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'kode_transaksi',
        'tanggal_transaksi',
        'mobil_id',
        'penjual_id',
        'harga_beli_mobil_final',
        'status_pembayaran',
        'bukti_pembayaran_file',
        'keterangan',
        'user_id'
    ];

    // Relasi: TransaksiPembelian memiliki satu Mobil
    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id');
    }

    // Relasi: TransaksiPembelian memiliki satu Penjual
    public function penjual()
    {
        return $this->belongsTo(Penjual::class, 'penjual_id');
    }

    // Relasi: TransaksiPembelian dibuat oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: TransaksiPembelian memiliki banyak TransaksiPembayaranDetail
    public function detailPembayaran()
    {
        return $this->hasMany(TransaksiPembayaranDetail::class, 'transaksi_id');
    }
}
