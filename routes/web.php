<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\LaporanController; // Pastikan ini ada
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SalesController;

// ------------------------
// 👤 RUTE UNTUK TAMU (GUEST ROUTES)
// ------------------------
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
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

    // ✅ CRUD Data Penjual
    Route::resource('penjual', PenjualController::class)
        ->middleware('role:manajer,admin');

    // ✅ Rute Resource untuk Transaksi Pembelian Mobil
    Route::resource('transaksi-pembelian', TransaksiPembelianController::class)
        ->middleware('role:manajer,admin,sales');

    // ✅ Rute Resource untuk Transaksi Penjualan Mobil
    Route::resource('transaksi-penjualan', TransaksiPenjualanController::class)
        ->parameters(['transaksi-penjualan' => 'transaksi_penjualan'])
        ->middleware('role:manajer,admin,sales');

    // ----------------------------------------------------
    // START: RUTE TRANSAKSI (Prefix 'transaksi')
    // ----------------------------------------------------
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])
            ->middleware('role:manajer,admin')
            ->name('index');

        Route::get('/pembeli', [TransaksiController::class, 'indexPembeli'])
            ->middleware('role:manajer,admin')
            ->name('pembeli.index');

        Route::get('/pembeli/create', [TransaksiController::class, 'createPembeli'])
            ->middleware('role:manajer,admin')
            ->name('pembeli.create');
        Route::post('/pembeli', [TransaksiController::class, 'storePembeli'])
            ->middleware('role:manajer,admin')
            ->name('pembeli.store');
        Route::get('/pembeli/{transaksi}/edit', [TransaksiController::class, 'editPembeli'])
            ->middleware('role:manajer,admin')
            ->name('pembeli.edit');
        Route::patch('/pembeli/{transaksi}', [TransaksiController::class, 'updatePembeli'])
            ->middleware('role:manajer,admin')
            ->name('pembeli.update');

        // Rute Resource untuk Transaksi Penjual (dalam prefix transaksi)
        // Catatan: Ini sepertinya salah penamaan, seharusnya TransaksiPenjualController bukan PenjualController
        // Jika Anda memiliki TransaksiPenjualController, pastikan diimpor di atas.
        // Jika ini memang untuk CRUD Penjual, maka resource 'penjual' sudah ada di luar prefix 'transaksi'.
        // Saya asumsikan ini adalah kesalahan ketik dan Anda bermaksud TransaksiPenjualController.
        // Jika tidak, harap klarifikasi.
        // Route::resource('penjual', TransaksiPenjualController::class)
        //     ->middleware('role:manajer,admin');

        // Detail servis (AJAX)
        Route::get('/get-servis-details', [TransaksiController::class, 'getServisDetails'])
            ->middleware('role:manajer,admin')
            ->name('getServisDetails');

        // Detail transaksi & delete
        Route::get('/{transaksi}', [TransaksiController::class, 'show'])
            ->middleware('role:manajer,admin')
            ->name('show');
        Route::delete('/{transaksi}', [TransaksiController::class, 'destroy'])
            ->middleware('role:manajer,admin')
            ->name('destroy');
    });
    // ----------------------------------------------------
    // END: RUTE TRANSAKSI
    // ----------------------------------------------------

    // ✅ Servis Routes - CRUD Servis
    Route::resource('servis', ServisController::class)
        ->parameters(['servis' => 'servis'])
        ->middleware('role:manajer,admin');

    // ----------------------------------------------------
    // START: RUTE LAPORAN
    // ----------------------------------------------------
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // --- Rute Baru/Diperbarui untuk Laporan Mobil Terjual ---
        Route::get('/mobil-terjual', [LaporanController::class, 'mobilTerjual'])->name('mobil_terjual');
        Route::get('/mobil-terjual/pdf', [LaporanController::class, 'exportMobilTerjualPdf'])->name('mobil_terjual.pdf');

        Route::get('/mobil-dibeli', [LaporanController::class, 'mobilDibeli'])->name('mobil_dibeli');
        // Route untuk ekspor PDF dari laporan mobil dibeli
        Route::get('/mobil-dibeli/pdf', [LaporanController::class, 'exportMobilDibeliPdf'])->name('mobil_dibeli.pdf');

        // Rute baru untuk laporan servis mobil
        Route::get('/mobil-servis', [LaporanController::class, 'mobilServis'])->name('mobil_servis');
        Route::get('/mobil-servis/pdf', [LaporanController::class, 'exportMobilServisPdf'])->name('mobil_servis.pdf');
    });
    // ----------------------------------------------------
    // END: RUTE LAPORAN
    // ----------------------------------------------------

    // ✅ Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ====================================================
    // START: RUTE PENGELOLAAN PENGGUNA (USERS MANAGEMENT)
    // ====================================================
    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('role:manajer,admin');

    Route::middleware('role:manajer')->get('/dashboard/manajer', [UserController::class, 'index'])->name('dashboard.manajer');

    // ====================================================
    // END: RUTE PENGELOLAAN PENGGUNA (USERS MANAGEMENT)
    // ====================================================

    // ✅ Dashboard per Role lainnya
    Route::middleware('role:admin')->get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

    Route::middleware('role:sales')->get('/dashboard/sales', [SalesController::class, 'index'])->name('dashboard.sales');

});

// ------------------------
// 🔁 RESET KATA SANDI (RESET PASSWORD)
// ------------------------
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
