<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'varian_produk_id', 'jumlah'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function varianProduk()
    {
        return $this->belongsTo(VarianProduk::class);
    }
}