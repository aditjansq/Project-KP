<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Menambahkan kolom yang dapat diisi secara massal
    protected $fillable = [
        'servis_id',      // kolom yang menghubungkan dengan servis
        'item_name',      // Nama Barang (Servis)
        'item_package',   // Kemasan
        'item_qty',       // Qty
        'item_price',     // Harga Satuan
        'item_discount',  // Diskon (%)
        'item_discount_value', // Nilai Diskon
        'item_total',     // Jumlah (setelah diskon)
        'service_date',   // Tanggal Servis
    ];

    /**
     * Relasi antara Item dan Servis (many to one)
     */
    public function servis()
    {
        return $this->belongsTo(Servis::class);  // Menghubungkan dengan model Servis
    }
}
