<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('ID Penjual');
            $table->foreignId('produk_id')->constrained();
            $table->string('pembeli'); // Nama pembeli
            $table->unsignedInteger('jumlah_terjual');
            $table->unsignedInteger('total_harga');
            $table->string('status'); // Cth: Selesai, Diproses
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
