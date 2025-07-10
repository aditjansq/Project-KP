<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiKreditDetail extends Model
{
    protected $table = 'transaksi_kredit_details';

    protected $fillable = [
        'transaksi_penjualan_id',
        'dp',
        'tempo',
        'leasing',
        'angsuran_per_bulan',
    ];

    /**
     * Relasi balik ke TransaksiPenjualan
     */
    public function transaksiPenjualan(): BelongsTo
    {
        return $this->belongsTo(TransaksiPenjualan::class);
    }
}
