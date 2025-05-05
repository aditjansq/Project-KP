<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\LoginLog;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


Route::get('/', function () {
    return view('welcome');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // OTP Verification
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.form'); // <- ini yang dipakai
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
});

// Hanya untuk user yang login dan sudah verifikasi email
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (!auth()->user()->email_verified) {
            return redirect()->route('otp.form')->withErrors([
                'otp' => 'Silakan verifikasi email Anda terlebih dahulu.'
            ]);
        }

        $loginLogs = LoginLog::where('user_id', auth()->id())
                             ->latest()
                             ->limit(10)
                             ->get();
        return view('dashboard', compact('loginLogs'));
    });
});


Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
