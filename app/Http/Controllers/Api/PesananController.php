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
    // Lihat Pesanan 
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Pesanan::with('metodePembayaran')
            ->where('user_id', $user->id);

        // Filter status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pesanans = $query->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesanan berhasil diambil',
            'data' => $pesanans
        ]);
    }

    // Lihat Detail Pesanan & Pengiriman
    public function show(Request $request, $id)
    {
        $pesanan = Pesanan::with([
            'metodePembayaran',
            'detailPesanan.varianProduk.produk',
            'pengiriman'
        ])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil diambil',
            'data' => $pesanan
        ]);
    }

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

        if (!$request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email Anda belum diverifikasi. Silakan verifikasi terlebih dahulu.'
            ], 403);
        }

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

    // Melakukan Pembayaran
    public function bayar(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $pesanan = Pesanan::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        if ($pesanan->status !== 'menunggu_pembayaran') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak memerlukan pembayaran atau sudah dibayar'
            ], 422);
        }

        if ($pesanan->metodePembayaran->tipe === 'cod') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan dengan metode COD tidak memerlukan bukti pembayaran'
            ], 422);
        }

        // Upload file ke folder bukti_pembayaran
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Update pesanan
        $pesanan->update([
            'bukti_pembayaran' => $path,
            'status' => 'menunggu_konfirmasi'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diunggah. Pesanan akan segera diproses.',
            'data' => [
                'bukti_pembayaran_url' => asset('storage/' . $path)
            ]
        ]);
    }
}
