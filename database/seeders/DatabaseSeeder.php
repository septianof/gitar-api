<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder utama, panggil semua seeder lain
        $this->call([
            UserSeeder::class,
            MetodePembayaranSeeder::class,
            ProdukSeeder::class,
        ]);
    }
}
