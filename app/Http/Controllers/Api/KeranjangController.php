<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // Lihat semua produk di keranjang user
    public function index(Request $request)
    {
        $user = $request->user();

        $items = Keranjang::with(['varianProduk.produk', 'varianProduk.produk.produkFoto'])
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data keranjang berhasil diambil',
            'data' => $items
        ]);
    }

    // Tambah produk ke keranjang
    public function store(Request $request)
    {
        $request->validate([
            'varian_produk_id' => 'required|exists:varian_produks,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $user = $request->user();

        $varian = VarianProduk::findOrFail($request->varian_produk_id);

        // Ambil jumlah lama di keranjang jika sudah ada
        $existing = Keranjang::where('user_id', $user->id)
            ->where('varian_produk_id', $request->varian_produk_id)
            ->first();

        $jumlahBaru = $request->jumlah + ($existing->jumlah ?? 0);

        if ($jumlahBaru > $varian->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia'
            ], 422);
        }

        if ($existing) {
            $existing->update([
                'jumlah' => $jumlahBaru
            ]);
            $data = $existing;
        } else {
            $data = Keranjang::create([
                'user_id' => $user->id,
                'varian_produk_id' => $request->varian_produk_id,
                'jumlah' => $request->jumlah
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang',
            'data' => $data
        ]);
    }

    // Ubah jumlah produk di keranjang
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $item = Keranjang::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        $stok = $item->varianProduk->stok;

        if ($request->jumlah > $stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia'
            ], 422);
        }

        $item->update(['jumlah' => $request->jumlah]);

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk diupdate',
            'data' => $item
        ]);
    }

    // Hapus item dari keranjang
    public function destroy(Request $request, $id)
    {
        $item = Keranjang::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari keranjang'
        ]);
    }
}
