<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualanPembayaranDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi_penjualan_pembayaran_details'; // Nama tabel baru

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaksi_id', // Foreign key yang menunjuk ke transaksi penjualan
        'metode_pembayaran_detail',
        'jumlah_pembayaran',
        'tanggal_pembayaran',
        'keterangan_pembayaran_detail',
        'bukti_pembayaran_detail', // Nama kolom untuk bukti pembayaran
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pembayaran' => 'date',
    ];

    /**
     * Get the TransaksiPenjualan that owns the payment detail.
     */
    public function transaksiPenjualan()
    {
        // Relasi ke model TransaksiPenjualan menggunakan 'transaksi_id'
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_id');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
