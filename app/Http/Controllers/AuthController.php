<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard'); // Pengguna sudah login, arahkan ke dashboard
        }
        return view('auth.login'); // View: resources/views/auth/login.blade.php
    }

    // Menangani proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // Arahkan ke halaman dashboard jika login sukses
        }

        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ]);
    }

    // Menampilkan form register
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard'); // Pengguna sudah login, arahkan ke dashboard
        }
        return view('auth.register'); // View: resources/views/auth/register.blade.php
    }

    // Menangani proses register
    public function register(Request $request)
    {
        // Validasi input pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'job' => 'required|in:manajer,divisi marketing,staff service,divisi finance',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Buat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'job' => $request->job,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Arahkan ke halaman dashboard setelah registrasi dan login
        return redirect()->intended('/dashboard');
    }

    // Menangani proses logout
    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();
        $request->session()->invalidate();  // Menghapus session
        $request->session()->regenerateToken();  // Regenerasi token CSRF untuk keamanan

        // Arahkan pengguna ke halaman login setelah logout
        return redirect('/login');
    }
}
