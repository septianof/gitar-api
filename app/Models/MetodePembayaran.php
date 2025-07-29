<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodePembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tipe', 'nama', 'nomor', 'gambar'];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}