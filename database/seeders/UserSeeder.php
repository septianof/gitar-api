<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        User::create([
            'nama' => 'Owner Gitar',
            'email' => 'owner@gitarshop.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Pemilik Utama',
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        // Admin
        User::create([
            'nama' => 'Admin Gitar',
            'email' => 'admin@gitarshop.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Admin Shop',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Customer
        User::create([
            'nama' => 'Budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Pelanggan Setia',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
    }
}