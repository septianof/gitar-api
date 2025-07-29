<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\PasswordOtpReset;
use Illuminate\Support\Str;
use Carbon\Carbon;


class ForgotPasswordController extends Controller
{
    // Generate Otp
    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;

        // Deteksi jenis pengiriman: email atau whatsapp
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $via = 'email';
        } elseif (preg_match('/^[0-9]{9,15}$/', $identifier)) {
            $via = 'whatsapp';
        } else {
            return response()->json(['message' => 'Format tidak valid'], 422);
        }

        // Generate OTP
        $otp = random_int(100000, 999999);

        // Simpan ke database
        PasswordOtpReset::create([
            'identifier' => $identifier,
            'via' => $via,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP
        if ($via === 'email') {
            Mail::raw("Kode OTP Anda: $otp", function ($message) use ($identifier) {
                $message->to($identifier)->subject('OTP Reset Password');
            });
        } else {
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $identifier, // nomor tujuan, contoh: 6281234567890
                'message' => "Kode OTP Anda: $otp",
                'countryCode' => '62', // opsional, jika `target` tidak pakai awalan 62
            ]);
        }

        return response()->json(['message' => "OTP dikirim via $via"], 200);
    }

    // Verifikasi Otp
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = PasswordOtpReset::where('identifier', $request->identifier)
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'OTP salah atau tidak ditemukan'], 404);
        }

        if ($otpRecord->isExpired()) {
            return response()->json(['message' => 'OTP sudah kadaluarsa'], 422);
        }

        if ($otpRecord->isVerified()) {
            return response()->json(['message' => 'OTP sudah digunakan'], 422);
        }

        // Tandai sebagai diverifikasi dan buat token
        $resetToken = Str::uuid();
        $otpRecord->update([
            'verified_at' => now(),
            'otp' => $resetToken, // timpa OTP jadi token
        ]);

        return response()->json([
            'message' => 'OTP terverifikasi',
            'reset_token' => $resetToken
        ]);
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string|uuid',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cari berdasarkan token (di kolom OTP yang sudah ditimpa)
        $otpRecord = PasswordOtpReset::where('otp', $request->reset_token)->first();

        if (!$otpRecord || !$otpRecord->isVerified()) {
            return response()->json(['message' => 'Token tidak valid'], 403);
        }

        if ($otpRecord->isExpired()) {
            return response()->json(['message' => 'Token sudah kadaluarsa'], 422);
        }

        // Temukan user dari identifier
        $user = User::where('email', $otpRecord->identifier)
            ->orWhere('no_hp', $otpRecord->identifier)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Bersihkan semua entri OTP milik user ini
        PasswordOtpReset::where('identifier', $otpRecord->identifier)->delete();

        return response()->json(['message' => 'Password berhasil direset. Silakan login.']);
    }
}
