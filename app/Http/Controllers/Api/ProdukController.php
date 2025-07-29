<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Ambil semua produk
    public function index()
    {
        $produks = Produk::with(['fotoUtama', 'varianProduk'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk',
            'data' => $produks
        ]);
    }

    // Ambil detail produk berdasarkan ID
    public function show($id)
    {
        $produk = Produk::with(['produkFoto', 'varianProduk'])->find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail produk',
            'data' => $produk
        ]);
    }
}