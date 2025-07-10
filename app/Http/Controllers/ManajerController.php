<?php

namespace App\Http\Controllers;

use App\Models\User; // Pastikan Anda mengimpor model User
use Illuminate\Http\Request;

class ManajerController extends Controller
{
    public function index()
    {
        // Ambil semua pengguna dari database, dengan pagination
        // Anda bisa menyesuaikan jumlah item per halaman (misal: 10)
        $users = User::paginate(10);

        // Teruskan variabel $users ke view manajer.blade.php
        return view('manajer', compact('users'));
    }

    // ... method controller lainnya jika ada
}
