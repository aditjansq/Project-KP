<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar jika tidak sesuai konvensi Laravel (misal: 'items')
    protected $table = 'items'; // Pastikan ini sesuai dengan nama tabel Anda

    // Menambahkan kolom yang dapat diisi secara massal
    // SESUAIKAN DENGAN NAMA KOLOM DI DATABASE ANDA SEPERTI PADA GAMBAR
    protected $fillable = [
        'servis_id',           // Kolom yang menghubungkan dengan servis
        'item_name',           // Nama Barang (Servis) - SESUAIKAN DENGAN SKEMA DB
        'item_package',        // Kemasan - SESUAIKAN DENGAN SKEMA DB
        'item_qty',            // Qty - SESUAIKAN DENGAN SKEMA DB
        'item_price',          // Harga Satuan - SESUAIKAN DENGAN SKEMA DB
        'item_discount',       // Diskon (%) - SESUAIKAN DENGAN SKEMA DB
        'item_discount_value', // Nilai Diskon - SESUAIKAN DENGAN SKEMA DB
        'item_total',          // Jumlah (setelah diskon) - SESUAIKAN DENGAN SKEMA DB
        'service_date',        // Tanggal Servis - SESUAIKAN DENGAN SKEMA DB
    ];

    /**
     * Relasi antara Item dan Servis (many to one)
     */
    public function servis()
    {
        return $this->belongsTo(Servis::class);  // Menghubungkan dengan model Servis
    }
}
