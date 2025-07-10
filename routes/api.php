<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Pastikan TransaksiController diimpor jika Anda masih menggunakannya untuk rute lain
// use App\Http\Controllers\TransaksiController;

// Impor ServisController karena metode getServisHistoryByMobilId ada di sini
use App\Http\Controllers\ServisController;
use App\Http\Controllers\Api\PembeliController; // Impor PembeliController

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rute untuk mendapatkan informasi pengguna yang terautentikasi
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rute untuk mendapatkan riwayat servis berdasarkan ID mobil
Route::get('/mobil/{mobilId}/servis-history', [ServisController::class, 'getServisHistoryByMobilId']);

// Rute untuk mendapatkan detail pembeli berdasarkan ID
// In your routes/web.php or routes/api.php
Route::get('/api/pembeli/{id}', [PembeliController::class, 'show']);
