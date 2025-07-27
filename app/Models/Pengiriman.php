<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id', 'ekspedisi', 'nama_kurir', 'nomor_resi'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}