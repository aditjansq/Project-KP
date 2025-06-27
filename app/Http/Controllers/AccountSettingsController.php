<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountSettingsController extends Controller
{
    // Method untuk menampilkan form pengaturan akun
    public function show()
    {
        return view('settings.profile', ['user' => auth()->user()]);
    }

    // Method untuk memperbarui pengaturan akun
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi inputan
        $validatedData = $request->validate([
            'current_password' => 'required',  // Validasi password saat ini
            'password' => 'nullable|string|min:8|confirmed', // Validasi password baru (opsional)
        ]);

        // Cek apakah current password sesuai dengan yang ada di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi saat ini tidak sesuai.',
            ]);
        }

        // Jika ada password baru yang diinputkan, kita perbarui password
        if ($request->password) {
            // Update password baru
            $user->password = Hash::make($request->password); // Enkripsi password baru
        }

        // Simpan perubahan password di database
        $user->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('settings')->with('success', 'Password Anda berhasil diperbarui.');
    }
}

