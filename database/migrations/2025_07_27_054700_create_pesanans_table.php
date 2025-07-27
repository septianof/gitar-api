<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('metode_pembayaran_id')->constrained('metode_pembayarans')->onDelete('cascade');
            $table->integer('total_harga');
            $table->enum('status', ['menunggu_pembayaran', 'menunggu_konfirmasi', 'dikemas', 'dikirim', 'selesai', 'dibatalkan']);
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
