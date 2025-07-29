<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Register Customer
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => 'customer',
        ]);

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Registrasi berhasil, verifikasi email dikirim',
            'user' => $user
        ], 201);
    }

    // Login & Ambil Token
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'Email tidak ditemukan',
                'debug' => 'User null'
            ], 404);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password salah',
                'debug' => 'Hash check gagal'
            ], 401);
        }

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    // Logout & hapus token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    // Lupa Password 
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link reset password telah dikirim ke email Anda.'
            ], 200);
        }

        return response()->json([
            'message' => 'Gagal mengirim link reset password.',
            'debug' => __($status)
        ], 500);
    }
}
