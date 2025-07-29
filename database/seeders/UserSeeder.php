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
            'nama' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('owner123'),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Pemilik Utama',
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        // Admin
        User::create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Admin Shop',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Customer Sudah Verifikasi email
        User::create([
            'nama' => 'Isah',
            'email' => 'isah@gmail.com',
            'password' => Hash::make('isah123'),
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Pelanggan Setia',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Customer Belum Verifikasi email
        User::create([
            'nama' => 'Tian',
            'email' => 'tian@gmail.com',
            'password' => Hash::make('tian123'),
            'no_hp' => '081234567893',
            'alamat' => 'Jl. Pelanggan Setia',
            'role' => 'customer',
        ]);
    }
}