<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'login_logs';

    // Tentukan kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'login_at',
    ];

    // Jika tidak ingin mengisi kolom timestamps otomatis, matikan
    public $timestamps = true;
}
