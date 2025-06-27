<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\LaporanController;

// ------------------------
// 👤 RUTE UNTUK TAMU (GUEST ROUTES)
// ------------------------
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome'); // Halaman depan jika pengguna belum login
    });

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // ✅ Verifikasi OTP
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
});


// ------------------------
// ✅ RUTE TERLINDUNGI (AUTH + VERIFIED ROUTES)
// ------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/settings', [AccountSettingsController::class, 'show'])->name('settings');
    Route::post('/settings', [AccountSettingsController::class, 'update'])->name('settings.update');

    // ✅ Redirect dinamis ke dashboard sesuai job
    Route::get('/dashboard', function () {
        if (!auth()->check() || !auth()->user()->is_verified) {
            return redirect()->route('otp.form')->withErrors([
                'otp' => 'Silakan verifikasi email Anda terlebih dahulu.'
            ]);
        }

        $job = strtolower(str_replace(' ', '-', auth()->user()->job));
        return redirect("/dashboard/{$job}");
    })->name('dashboard');

    // ✅ CRUD Data Mobil
    Route::resource('mobil', MobilController::class)
        ->middleware('role:manajer,admin,sales');

    // ✅ CRUD Data Pembeli
    Route::resource('pembeli', PembeliController::class)
        ->middleware('role:manajer,admin');

    // CRUD DATA PENJUAL
    Route::resource('penjual', PenjualController::class)
        ->middleware('role:manajer,admin');

    // ✅ Transaksi - hanya manajer dan admin
    Route::get('/transaksi', [TransaksiController::class, 'index'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.index');

    // Menambahkan rute untuk submenu Transaksi Pembeli dan Penjual
    Route::get('/transaksi/pembeli', [TransaksiController::class, 'indexPembeli'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.pembeli.index');
    Route::get('/transaksi/pembeli/create', [TransaksiController::class, 'createPembeli'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.pembeli.create');
    Route::post('/transaksi/pembeli', [TransaksiController::class, 'storePembeli'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.pembeli.store');
    // Rute baru untuk edit dan update transaksi pembeli
    Route::get('/transaksi/pembeli/{transaksi}/edit', [TransaksiController::class, 'editPembeli'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.pembeli.edit');
    Route::patch('/transaksi/pembeli/{transaksi}', [TransaksiController::class, 'updatePembeli'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.pembeli.update');
    // Rute untuk menampilkan detail transaksi (digunakan oleh modal detail di index)
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.show');


    Route::get('/transaksi/penjual', [TransaksiController::class, 'indexPenjual'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.penjual.index');
    Route::get('/transaksi/penjual/create', [TransaksiController::class, 'createPenjual'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.penjual.create');
    Route::post('/transaksi/penjual', [TransaksiController::class, 'storePenjual'])
        ->middleware('role:manajer,admin')
        ->name('transaksi.penjual.store');

    // ✅ Servis Routes - CRUD Servis
    Route::resource('servis', ServisController::class)
        ->parameters(['servis' => 'servis']) // Explicitly define parameter name for 'servis' resource
        ->middleware('role:manajer,admin');

    // ----------------------------------------------------
    // START: RUTE LAPORAN
    // ----------------------------------------------------
    // ✅ Laporan - hanya manajer dan admin
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/mobil-terjual', [LaporanController::class, 'mobilTerjual'])->name('mobil_terjual');
        Route::get('/mobil-dibeli', [LaporanController::class, 'mobilDibeli'])->name('mobil_dibeli');
        // Tambahkan rute laporan lain di sini jika diperlukan
    });
    // ----------------------------------------------------
    // END: RUTE LAPORAN
    // ----------------------------------------------------

    // ✅ Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ✅ Dashboard per Role
    Route::middleware('role:manajer')->get('/dashboard/manajer', function () {
        return view('roles.manajer');
    });

    Route::middleware('role:admin')->get('/dashboard/admin', function () {
        return view('roles.admin');
    });

    Route::middleware('role:sales')->get('/dashboard/sales', function () {
        return view('roles.sales');
    });
});

// ------------------------
// 🔁 RESET KATA SANDI (RESET PASSWORD)
// ------------------------
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
