<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\LoginLog;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Cek apakah email sudah diverifikasi
            if (!Auth::user()->email_verified) {
                Auth::logout();
                return redirect()->route('otp.verify.form')
                    ->withErrors(['email' => 'Silakan verifikasi email Anda terlebih dahulu.']);
            }

            $request->session()->regenerate();

            LoginLog::create([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Menampilkan form register
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.register');
    }

    // Menangani proses registrasi dan kirim OTP
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'job' => 'required|in:manajer,divisi marketing,staff service,divisi finance',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $otp = rand(100000, 999999); // Membuat OTP acak 6 digit

        // Membuat user baru dan menyimpan OTP
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'job' => $request->job,
            'password' => Hash::make($request->password),
            'otp' => $otp,  // Menyimpan OTP
            'email_verified' => false,
            'otp_sent_at' => now(),  // Menyimpan waktu pengiriman OTP
        ]);

        // Kirim OTP ke email
        Mail::to($user->email)->send(new OtpMail($otp));
        session(['otp_user_id' => $user->id]);  // Menyimpan user_id dalam session

        return redirect()->route('otp.form')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }


    // Menampilkan form OTP
    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    // Menangani verifikasi OTP
    public function verifyOtp(Request $request)
    {
        // Validasi input OTP
        $request->validate(['otp' => 'required|digits:6']);
    
        // Ambil user berdasarkan session
        $user = User::find(session('otp_user_id'));
    
        // Pastikan user ada dan OTP yang dimasukkan sesuai
        if (!$user) {
            return back()->withErrors(['otp' => 'User tidak ditemukan.']);
        }
    
        // Cek apakah OTP sudah kadaluarsa (misalnya 10 menit)
        if (now()->diffInMinutes($user->otp_sent_at) > 10) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.']);
        }
    
        // Verifikasi OTP yang dimasukkan
        if ($user->otp == $request->otp) {
            $user->email_verified = true;
            $user->otp = null;  // Kosongkan OTP setelah verifikasi
            $user->save();
    
            Auth::login($user);
            session()->forget('otp_user_id');  // Hapus session OTP
    
            return redirect('/dashboard')->with('success', 'Email berhasil diverifikasi!');
        }
    
        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah tidak berlaku.']);
    }
    
    // Menampilkan form permintaan reset password
    public function showEmailForm()
    {
        return view('auth.passwords.email');
    }

    // Mengirimkan OTP untuk reset password
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);
        $user = User::where('email', $request->email)->first();

        $user->otp = $otp;
        $user->otp_sent_at = now();
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));

        return back()->with('status', 'OTP telah dikirimkan ke email Anda.');
    }

    // Menampilkan form reset password
    public function showResetPasswordForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    // Memverifikasi OTP dan reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::where('otp', $request->otp)->first();

        if (!$user || now()->diffInMinutes($user->otp_sent_at) > 10) {
            return back()->withErrors(['otp' => 'OTP tidak valid atau sudah kadaluarsa.']);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->save();

        return redirect('/login')->with('success', 'Password berhasil diubah. Silakan login.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
