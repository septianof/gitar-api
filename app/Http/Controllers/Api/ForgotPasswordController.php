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
    // Kirim OTP ke email atau WhatsApp
    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // email atau no_hp
        ]);

        $identifier = $request->identifier;

        // Deteksi jenis tujuan: email atau whatsapp
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $via = 'email';
        } elseif (preg_match('/^[0-9]{9,15}$/', $identifier)) {
            $via = 'whatsapp';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Format identifier tidak valid. Harus email atau nomor HP.'
            ], 422);
        }

        // Generate OTP 6 digit
        $otp = random_int(100000, 999999);

        // Simpan ke database
        PasswordOtpReset::create([
            'identifier' => $identifier,
            'via' => $via,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP via Email atau WhatsApp
        if ($via === 'email') {
            Mail::raw("Kode OTP Anda: $otp", function ($message) use ($identifier) {
                $message->to($identifier)->subject('OTP Reset Password');
            });
        } else {
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $identifier,
                'message' => "Kode OTP Anda: $otp",
                'countryCode' => '62',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "OTP berhasil dikirim via $via"
        ]);
    }

    // Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        // Ambil OTP record terbaru berdasarkan identifier dan otp
        $otpRecord = PasswordOtpReset::where('identifier', $request->identifier)
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'OTP salah atau tidak ditemukan'
            ], 404);
        }

        if ($otpRecord->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP sudah kedaluwarsa'
            ], 422);
        }

        if ($otpRecord->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP sudah digunakan'
            ], 422);
        }

        // Tandai OTP sebagai diverifikasi dan generate reset_token (UUID)
        $resetToken = Str::uuid();
        $otpRecord->update([
            'verified_at' => now(),
            'otp' => $resetToken, // Timpa OTP dengan token
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil diverifikasi',
            'data' => [
                'reset_token' => $resetToken
            ]
        ]);
    }

    // Reset password dengan token
    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string|uuid',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cari token (disimpan di kolom `otp` setelah diverifikasi)
        $otpRecord = PasswordOtpReset::where('otp', $request->reset_token)->first();

        if (!$otpRecord || !$otpRecord->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau belum diverifikasi'
            ], 403);
        }

        if ($otpRecord->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah kedaluwarsa'
            ], 422);
        }

        // Cari user berdasarkan email / no_hp dari OTP record
        $user = User::where('email', $otpRecord->identifier)
            ->orWhere('no_hp', $otpRecord->identifier)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus semua OTP record milik identifier tersebut
        PasswordOtpReset::where('identifier', $otpRecord->identifier)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset. Silakan login.'
        ]);
    }
}