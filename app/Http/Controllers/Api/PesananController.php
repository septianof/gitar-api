<?php

namespace App\Http\Controllers\API;

use App\Models\Pesanan;
use App\Models\Keranjang;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran;
use App\Http\Controllers\Controller;

class PesananController extends Controller
{
    // Buat Pesanan
    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran_id' => 'required|exists:metode_pembayarans,id',
            'items' => 'required|array|min:1',
            'items.*.varian_produk_id' => 'required|exists:varian_produks,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $totalHarga = 0;
        $detailItems = [];

        foreach ($request->items as $item) {
            $varian = VarianProduk::find($item['varian_produk_id']);

            if ($item['jumlah'] > $varian->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi untuk varian ID: ' . $varian->id
                ], 422);
            }

            $subtotal = $item['jumlah'] * $varian->harga;
            $totalHarga += $subtotal;

            $detailItems[] = [
                'varian_produk_id' => $varian->id,
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $varian->harga,
                'subtotal' => $subtotal,
            ];
        }

        // Cek metode pembayaran
        $metode = MetodePembayaran::find($request->metode_pembayaran_id);
        $status = $metode->tipe === 'cod' ? 'menunggu_konfirmasi' : 'menunggu_pembayaran';

        // Simpan pesanan
        $pesanan = Pesanan::create([
            'user_id' => $user->id,
            'metode_pembayaran_id' => $metode->id,
            'total_harga' => $totalHarga,
            'status' => $status,
        ]);

        // Simpan detail pesanan
        foreach ($detailItems as $detail) {
            $pesanan->detailPesanan()->create($detail);

            // Kurangi stok varian
            $varian = VarianProduk::find($detail['varian_produk_id']);
            $varian->decrement('stok', $detail['jumlah']);
        }

        // hapus produk dikeranjang user (yang telah di checkout) 
        foreach ($request->items as $item) {
            Keranjang::where('user_id', $user->id)
                ->where('varian_produk_id', $item['varian_produk_id'])
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => [
                'pesanan_id' => $pesanan->id,
                'status' => $status
            ]
        ]);
    }
}
