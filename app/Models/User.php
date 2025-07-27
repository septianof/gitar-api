<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nama', 'email', 'password', 'no_hp', 'alamat', 'role', 'email_verified_at'
    ];

    protected $hidden = ['password'];

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}

