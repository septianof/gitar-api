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
        // Tambahkan produk contoh
        $produk = Produk::create([
            'nama' => 'Gitar Akustik Yamaha F310',
            'kategori' => 'Akustik',
            'deskripsi' => 'Gitar akustik berkualitas untuk pemula hingga menengah.',
        ]);

        $produk = Produk::create([
            'nama' => 'Gitar Elektrik Scorpion SS120',
            'kategori' => 'Elektrik',
            'deskripsi' => 'Gitar elektrik berkualitas untuk pemula hingga menengah.',
        ]);

        // Tambahkan varian untuk produk tersebut
        VarianProduk::insert([
            [
                'produk_id' => $produk->id,
                'varian' => 'Natural',
                'harga' => 1500000,
                'stok' => 25,
            ],
            [
                'produk_id' => $produk->id,
                'varian' => 'Hitam',
                'harga' => 1550000,
                'stok' => 25,
            ],
        ]);

        VarianProduk::insert([
            [
                'produk_id' => $produk->id,
                'varian' => 'Blue',
                'harga' => 1500000,
                'stok' => 25,
            ],
            [
                'produk_id' => $produk->id,
                'varian' => 'White',
                'harga' => 1550000,
                'stok' => 25,
            ],
        ]);

        // Tambahkan foto untuk produk tersebut
        ProdukFoto::insert([
            [
                'produk_id' => $produk->id,
                'gambar' => 'yamaha1.jpg',
            ],
            [
                'produk_id' => $produk->id,
                'gambar' => 'yamaha2.jpg',
            ],
        ]);

        ProdukFoto::insert([
            [
                'produk_id' => $produk->id,
                'gambar' => 'scorpion1.jpg',
            ],
            [
                'produk_id' => $produk->id,
                'gambar' => 'scorpion2.jpg',
            ],
        ]);
    }
}