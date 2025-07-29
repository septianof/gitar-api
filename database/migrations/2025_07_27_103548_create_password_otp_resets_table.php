<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('password_otp_resets', function (Blueprint $table) {
            $table->id();

            // Alamat pengiriman (email atau nomor HP)
            $table->string('identifier'); // bisa email atau nomor HP
            $table->enum('via', ['email', 'whatsapp']); // asal OTP
            $table->string('otp'); // kode OTP (6 digit)
            $table->string('reset_token')->nullable()->unique();
            $table->timestamp('expired_at'); // kapan OTP kadaluarsa
            $table->timestamp('verified_at')->nullable(); // kapan OTP diverifikasi

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_otp_resets');
    }
};