<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama', 'kategori', 'deskripsi'];

    public function varianProduk()
    {
        return $this->hasMany(VarianProduk::class);
    }

    public function produkFoto()
    {
        return $this->hasMany(ProdukFoto::class);
    }

    public function fotoUtama()
    {
        return $this->hasOne(ProdukFoto::class)->oldest(); // Ambil foto pertama
    }
}