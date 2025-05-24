<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\TransaksiController;

// ------------------------
// 👤 GUEST ROUTES
// ------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // ✅ OTP Verification
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
});

// ------------------------
// ✅ AUTH + VERIFIED ROUTES
// ------------------------
Route::middleware(['auth'])->group(function () {

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
        ->middleware('role:manajer,divisi marketing,staff service,divisi finance');

    // ✅ CRUD Data Pembeli
    Route::resource('pembeli', PembeliController::class)
        ->middleware('role:manajer,divisi marketing,divisi finance');

    // ✅ Transaksi - hanya manajer dan finance
    Route::get('/transaksi', [TransaksiController::class, 'index'])
        ->middleware('role:manajer,divisi finance')
        ->name('transaksi.index');

    // ✅ Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ✅ Dashboard per Role
    Route::middleware('role:manajer')->get('/dashboard/manajer', function () {
        return view('roles.manajer');
    });

    Route::middleware('role:divisi marketing')->get('/dashboard/divisi-marketing', function () {
        return view('roles.marketing');
    });

    Route::middleware('role:staff service')->get('/dashboard/staff-service', function () {
        return view('roles.service');
    });

    Route::middleware('role:divisi finance')->get('/dashboard/divisi-finance', function () {
        return view('roles.finance');
    });
});

// ------------------------
// 🔁 RESET PASSWORD
// ------------------------
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
