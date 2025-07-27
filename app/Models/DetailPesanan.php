<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id', 'varian_produk_id', 'jumlah', 'harga_satuan', 'subtotal'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function varianProduk()
    {
        return $this->belongsTo(VarianProduk::class);
    }
}