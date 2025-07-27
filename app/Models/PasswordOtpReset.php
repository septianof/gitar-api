<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class PasswordOtpReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'via',
        'otp',
        'expired_at',
        'verified_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'verified_at' => 'datetime',
    ];
    
    // Mengecek apakah OTP sudah expired
    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    // Mengecek apakah OTP sudah diverifikasi
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
}