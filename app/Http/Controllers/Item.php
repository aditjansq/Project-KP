<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'servis_id', 
        'item_name', 
        'item_package', 
        'item_qty', 
        'item_price', 
        'item_discount', 
        'item_total', 
        'service_date',
    ];

    /**
     * Relasi antara Item dan Servis (many to one)
     */
    public function servis()
    {
        return $this->belongsTo(Servis::class);  // Menghubungkan ke servis
    }
}
