<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarianProduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['produk_id', 'varian', 'harga', 'stok'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}