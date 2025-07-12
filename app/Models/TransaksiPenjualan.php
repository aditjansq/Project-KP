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

    /**
     * Relasi dengan model Mobil.
     * Satu TransaksiPenjualan dimiliki oleh satu Mobil.
     */
    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    /**
     * Relasi dengan model Pembeli.
     * Satu TransaksiPenjualan dimiliki oleh satu Pembeli.
     */
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }

    /**
     * Relasi dengan detail kredit jika ada.
     * Satu TransaksiPenjualan memiliki satu TransaksiKreditDetail.
     */
    public function kreditDetail()
    {
        return $this->hasOne(TransaksiKreditDetail::class);
    }

    /**
     * Relasi dengan detail pembayaran.
     * Satu TransaksiPenjualan memiliki banyak TransaksiPenjualanPembayaranDetail.
     */
    public function pembayaranDetails()
    {
        return $this->hasMany(TransaksiPenjualanPembayaranDetail::class, 'transaksi_id');
    }

    /**
     * Boot method untuk mendaftarkan event listener.
     * Ketika TransaksiPenjualan baru dibuat, update ketersediaan mobil.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaksiPenjualan) {
            // Temukan mobil yang terkait dengan transaksi ini
            $mobil = Mobil::find($transaksiPenjualan->mobil_id);

            // Jika mobil ditemukan, perbarui status ketersediaannya menjadi 'terjual'
            if ($mobil) {
                $mobil->update(['ketersediaan' => 'terjual']);
            }
        });
    }
}
