<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    /**
     * Buat instance baru dengan OTP.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Judul email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Verifikasi Email Anda'
        );
    }

    /**
     * View email dan data yang dikirim ke view.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otp' => $this->otp,
            ]
        );
    }

    /**
     * Attachment email (kosong karena tidak ada).
     */
    public function attachments(): array
    {
        return [];
    }
}
