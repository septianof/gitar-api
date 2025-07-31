
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;

// Halaman pilih role sebelum login
Route::get('/', function () {
    return view('auth.choose-role');
});

// Verifikasi email
Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = User::findOrFail($id);

    // Validasi hash sama seperti Laravel bawaan
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link');
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email sudah terverifikasi']);
    }

    $user->markEmailAsVerified();

    return view('email-verified');
})->middleware(['signed'])->name('verification.verify');