<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'metode_pembayaran_id', 'total_harga', 'status', 'bukti_pembayaran'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }
}