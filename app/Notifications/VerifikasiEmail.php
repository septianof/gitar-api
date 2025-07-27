<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifikasiEmail extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email')
            ->line('Halo! Silakan klik tombol di bawah ini untuk verifikasi email Anda.')
            ->action('Verifikasi Sekarang', $verificationUrl)
            ->line('Jika Anda tidak membuat akun, abaikan email ini.');
    }
}

