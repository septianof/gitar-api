<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Ambil data profil user yang sedang login
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil',
            'data' => $request->user()
        ]);
    }

    // Edit data profil (nama, email, no_hp, alamat)
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'no_hp' => 'sometimes|string',
            'alamat' => 'sometimes|string',
        ]);

        $emailDiubah = false;

        // Jika email berubah, reset verifikasi dan kirim ulang
        if ($request->has('email') && $request->email !== $user->email) {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
            $emailDiubah = true;
        }

        // Update field lainnya jika dikirim
        if ($request->has('nama')) {
            $user->nama = $request->nama;
        }

        if ($request->has('no_hp')) {
            $user->no_hp = $request->no_hp;
        }

        if ($request->has('alamat')) {
            $user->alamat = $request->alamat;
        }

        $user->save();

        // Buat message sesuai konteks
        $message = $emailDiubah
            ? 'Profil berhasil diperbarui. Email diubah, link verifikasi telah dikirim.'
            : 'Profil berhasil diperbarui.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $user
        ]);
    }

    // Kirim ulang link verifikasi email
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah diverifikasi'
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Link verifikasi email telah dikirim'
        ]);
    }

    // Ganti Password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        $user = $request->user();

        // Cek apakah password lama cocok
        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai'
            ], 401);
        }

        // Ubah password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }
}
