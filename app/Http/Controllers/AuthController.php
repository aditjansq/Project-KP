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
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (!Auth::user()->is_verified) {
                Auth::logout();
            } else {
                // Redirect ke dashboard sesuai job
                $job = strtolower(str_replace(' ', '-', Auth::user()->job));
                return redirect("/dashboard/{$job}");
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->is_verified) {
                Auth::logout();
                return redirect()->route('otp.form')
                    ->withErrors(['email' => 'Silakan verifikasi email Anda terlebih dahulu.']);
            }

            $request->session()->regenerate();

            LoginLog::create([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Redirect ke dashboard sesuai job
            $job = strtolower(str_replace(' ', '-', Auth::user()->job));
            return redirect("/dashboard/{$job}");
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            // Redirect ke dashboard sesuai job
            $job = strtolower(str_replace(' ', '-', Auth::user()->job));
            return redirect("/dashboard/{$job}");
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi hanya menerima 'manajer', 'admin', atau 'sales' untuk job
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'job' => 'required|in:manajer,admin,sales', // Menambahkan 'admin' dan 'sales' dalam validasi job
            'password' => 'required|string|confirmed|min:8',
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'job' => $request->job,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'is_verified' => false,
            'otp_sent_at' => now(),
        ]);

        // Mengirim OTP ke email pengguna
        Mail::to($user->email)->send(new OtpMail($otp));
        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.form')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return back()->withErrors(['otp' => 'User tidak ditemukan.']);
        }

        if (now()->diffInMinutes($user->otp_sent_at) > 10) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.']);
        }

        if ($user->otp == $request->otp) {
            $user->is_verified = true;
            $user->otp = null;
            $user->save();

            Auth::login($user);
            session()->forget('otp_user_id');

            // Redirect ke dashboard sesuai job
            $job = strtolower(str_replace(' ', '-', $user->job));
            return redirect("/dashboard/{$job}")->with('success', 'Email berhasil diverifikasi!');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah tidak berlaku.']);
    }

    public function showEmailForm()
    {
        return view('auth.passwords.email');
    }

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

    public function showResetPasswordForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
