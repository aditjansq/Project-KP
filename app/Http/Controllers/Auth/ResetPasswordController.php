<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Menampilkan form reset password
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Mengubah password pengguna
     */
    public function reset(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required',
        ]);

        // Mencari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Verifikasi token reset password
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                // Trigger event Password Reset
                event(new PasswordReset($user));
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil diubah.');
        }

        return back()->withErrors(['email' => 'Gagal mereset password.']);
    }
}
