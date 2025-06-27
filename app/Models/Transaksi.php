<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksis';

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
     * PASTIKAN 'bukti_pembayaran' ADA DI DAFTAR INI DENGAN NAMA KOLOM YANG SAMA PERSIS DI DB.
     *
     * @var array
     */
    protected $fillable = [
        'mobil_id',
        'pembeli_id',
        'penjual_id',
        'kode_transaksi',
        'tanggal_transaksi',
        'metode_pembayaran',
        'diskon_persen',
        'total_harga',
        'keterangan',
        'status_pembayaran', // Pastikan nama kolom ini benar di DB Anda
        'bukti_pembayaran',  // <---- INI ADALAH KUNCI UTAMA ---->
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_transaksi' => 'date',
    ];

    /**
     * Get the Mobil that owns the Transaksi.
     */
    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id');
    }

    /**
     * Get the Pembeli that owns the Transaksi.
     */
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    /**
     * Get the Penjual that owns the Transaksi.
     */
    public function penjual()
    {
        return $this->belongsTo(Penjual::class, 'penjual_id');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
