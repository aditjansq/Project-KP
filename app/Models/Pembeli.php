<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $fillable = [
        'kode_pembeli',
        'nama',
        'tanggal_lahir',
        'pekerjaan',
        'alamat',
        'no_telepon',
    ];
}