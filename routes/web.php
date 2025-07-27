<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// [WAJIB] Route verifikasi email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // tandai sebagai terverifikasi
    return response()->json(['message' => 'Email berhasil diverifikasi']);
})->middleware(['auth', 'signed'])->name('verification.verify');

// [Opsional] Kirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Link verifikasi dikirim ulang']);
})->middleware(['auth'])->name('verification.send');