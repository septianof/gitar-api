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
        $yamaha = Produk::create([
            'nama' => 'Gitar Akustik Yamaha F310',
            'kategori' => 'Akustik',
            'deskripsi' => 'Gitar akustik berkualitas untuk pemula hingga menengah.',
        ]);

        $scorpion = Produk::create([
            'nama' => 'Gitar Elektrik Scorpion SS120',
            'kategori' => 'Elektrik',
            'deskripsi' => 'Gitar elektrik berkualitas untuk pemula hingga menengah.',
        ]);

        // Varian untuk Yamaha
        VarianProduk::insert([
            [
                'produk_id' => $yamaha->id,
                'varian' => 'Natural',
                'harga' => 1500000,
                'stok' => 25,
            ],
            [
                'produk_id' => $yamaha->id,
                'varian' => 'Hitam',
                'harga' => 1550000,
                'stok' => 25,
            ],
        ]);

        // Varian untuk Scorpion
        VarianProduk::insert([
            [
                'produk_id' => $scorpion->id,
                'varian' => 'Blue',
                'harga' => 1500000,
                'stok' => 25,
            ],
            [
                'produk_id' => $scorpion->id,
                'varian' => 'White',
                'harga' => 1550000,
                'stok' => 25,
            ],
        ]);

        // Foto untuk Yamaha
        ProdukFoto::insert([
            [
                'produk_id' => $yamaha->id,
                'gambar' => 'yamaha1.jpg',
            ],
            [
                'produk_id' => $yamaha->id,
                'gambar' => 'yamaha2.jpg',
            ],
        ]);

        // Foto untuk Scorpion
        ProdukFoto::insert([
            [
                'produk_id' => $scorpion->id,
                'gambar' => 'scorpion1.jpg',
            ],
            [
                'produk_id' => $scorpion->id,
                'gambar' => 'scorpion2.jpg',
            ],
        ]);
    }
}
