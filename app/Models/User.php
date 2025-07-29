<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifikasiEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
        'email_verified_at'
    ];

    protected $hidden = ['password'];

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifikasiEmail);
    }
}
