<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\VarianProduk;
use App\Models\ProdukFoto;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // Tambahkan 1 produk contoh
        $produk = Produk::create([
            'nama' => 'Gitar Akustik Yamaha F310',
            'kategori' => 'Akustik',
            'deskripsi' => 'Gitar akustik berkualitas untuk pemula hingga menengah.',
        ]);

        // Tambahkan varian untuk produk tersebut
        VarianProduk::insert([
            [
                'produk_id' => $produk->id,
                'varian' => 'Natural',
                'harga' => 1500000,
                'stok' => 10,
            ],
            [
                'produk_id' => $produk->id,
                'varian' => 'Hitam',
                'harga' => 1550000,
                'stok' => 8,
            ],
        ]);

        // Tambahkan foto untuk produk tersebut
        ProdukFoto::insert([
            [
                'produk_id' => $produk->id,
                'gambar' => 'gitar1.jpg',
            ],
            [
                'produk_id' => $produk->id,
                'gambar' => 'gitar2.jpg',
            ],
        ]);
    }
}