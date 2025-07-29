<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // Beberapa metode pembayaran dasar
        MetodePembayaran::insert([
            [
                'tipe' => 'transfer_bank',
                'nama' => 'BCA',
                'nomor' => '1234567890',
                'gambar' => 'bca.png',
            ],
            [
                'tipe' => 'ewallet',
                'nama' => 'DANA',
                'nomor' => '081234567891',
                'gambar' => 'dana.png',
            ],
            [
                'tipe' => 'qris',
                'nama' => 'QRIS',
                'nomor' => null,
                'gambar' => 'qris.png',
            ],
            [
                'tipe' => 'cod',
                'nama' => 'COD',
                'nomor' => null,
                'gambar' => null,
            ],
        ]);
    }
}