<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukFoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['produk_id', 'gambar'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}