<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\TransaksiController; // Pastikan ini diimpor
use App\Http\Controllers\ServisController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


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
        // Redirect ke route yang spesifik untuk setiap peran
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

    // ----------------------------------------------------
    // START: RUTE TRANSAKSI
    // ----------------------------------------------------
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        // Rute untuk daftar semua transaksi (umum)
        Route::get('/', [TransaksiController::class, 'index'])
            ->middleware('role:manajer,admin')
            ->name('index');

        // Rute untuk daftar transaksi Pembeli
        Route::get('/pembeli', [TransaksiController::class, 'indexPembeli'])
            ->middleware('role:manajer,admin')
            ->name('pembeli.index');

        // Rute untuk daftar transaksi Penjual
        Route::get('/penjual', [TransaksiController::class, 'indexPenjual'])
            ->middleware('role:manajer,admin')
            ->name('penjual.index');

        // Rute spesifik untuk membuat dan menyimpan transaksi PEMBELI
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

        // Rute spesifik untuk membuat dan menyimpan transaksi PENJUAL
        Route::get('/penjual/create', [TransaksiController::class, 'createPenjual'])
            ->middleware('role:manajer,admin')
            ->name('penjual.create');
        Route::post('/penjual', [TransaksiController::class, 'storePenjual'])
            ->middleware('role:manajer,admin')
            ->name('penjual.store');
        Route::get('/penjual/{transaksi}/edit', [TransaksiController::class, 'editPenjual'])
            ->middleware('role:manajer,admin')
            ->name('penjual.edit');
        Route::patch('/penjual/{transaksi}', [TransaksiController::class, 'updatePenjual'])
            ->middleware('role:manajer,admin')
            ->name('penjual.update');

        // Rute untuk mendapatkan detail servis mobil (digunakan oleh AJAX)
        Route::get('/get-servis-details', [TransaksiController::class, 'getServisDetails'])
            ->middleware('role:manajer,admin')
            ->name('getServisDetails');

        // Rute untuk menampilkan detail transaksi (digunakan oleh modal detail di index)
        // Ini harus diletakkan PALING AKHIR dari semua rute GET /transaksi/* yang spesifik
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

    // ====================================================
    // START: RUTE PENGELOLAAN PENGGUNA (USERS MANAGEMENT)
    // ====================================================
    // Rute Resource untuk pengelolaan pengguna, hanya bisa diakses oleh admin dan manajer
    // Menggunakan name 'users' untuk konsistensi
    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->middleware('role:manajer,admin');

    // Route untuk menampilkan dashboard manajer, yang akan menampilkan tabel users
    // Memanggil index dari UserController karena manajer mengelola user di situ
    Route::middleware('role:manajer')->get('/dashboard/manajer', [UserController::class, 'index'])->name('dashboard.manajer');
    // ====================================================
    // END: RUTE PENGELOLAAN PENGGUNA (USERS MANAGEMENT)
    // ====================================================


    // ✅ Dashboard per Role (lainnya)
    // ADMIN DASHBOARD: Sekarang memanggil AdminController::index
    Route::middleware('role:admin')->get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

    Route::middleware('role:sales')->get('/dashboard/sales', function () {
        return view('roles.sales');
    })->name('dashboard.sales');
});

// ------------------------
// 🔁 RESET KATA SANDI (RESET PASSWORD)
// ------------------------
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
