<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;  // Import controller yang tepat
use App\Models\LoginLog;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Menambahkan route untuk login dengan middleware 'guest' agar hanya bisa diakses oleh pengguna yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Melindungi route yang hanya bisa diakses pengguna yang sudah login
Route::middleware('auth')->get('/dashboard', function () {
    $loginLogs = LoginLog::where('user_id', auth()->id())
                         ->latest()
                         ->limit(10)
                         ->get();
    return view('dashboard', compact('loginLogs'));
});


// Menambahkan route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
