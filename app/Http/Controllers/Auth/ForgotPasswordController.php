<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan form untuk meminta reset password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengirimkan link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Mengirimkan link reset password melalui email
        $response = Password::sendResetLink(
            $request->only('email')
        );

        // Menampilkan pesan berdasarkan hasil pengiriman
        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
        } else {
            return back()->withErrors(['email' => 'Gagal mengirimkan link reset password.']);
        }
    }
}
