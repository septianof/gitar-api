<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengirimans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->string('ekspedisi');
            $table->string('nama_kurir');
            $table->string('nomor_resi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengirimans');
    }
};
