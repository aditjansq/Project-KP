<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiPembayaranDetail; // Import model baru

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
     * Kolom 'metode_pembayaran', 'dp_jumlah', dan 'bukti_pembayaran' dihapus dari fillable
     * karena detail pembayaran akan ditangani oleh relasi ke TransaksiPembayaranDetail.
     *
     * @var array
     */
    protected $fillable = [
        'mobil_id',
        'pembeli_id',
        'penjual_id',
        'kode_transaksi',
        'tanggal_transaksi',
        'tempo_angsuran', // Tetap dipertahankan jika relevan untuk transaksi kredit/angsuran keseluruhan
        'diskon_persen',
        'total_harga',
        'keterangan',
        'status_pembayaran',
        'servis_id',
        'modal',
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
     * Define the relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class); // Asumsi nama model User adalah User
    }

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
     * Get the Servis that owns the Transaksi.
     * Ini mendefinisikan relasi one-to-one atau many-to-one di mana Transaksi merujuk ke Servis.
     */
    public function servis()
    {
        return $this->belongsTo(Servis::class, 'servis_id');
    }

    /**
     * Get the payment details for the Transaksi.
     * Ini mendefinisikan relasi one-to-many ke TransaksiPembayaranDetail.
     */
    public function pembayaranDetails()
    {
        return $this->hasMany(TransaksiPembayaranDetail::class, 'transaksi_id');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
