<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_sampahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_sampah_id')->constrained()->onDelete('cascade');
            $table->string('kategori'); // Cth: Kertas & Kardus, Plastik, Logam
            $table->string('nama_item');   // Cth: Kertas Putih, Botol Plastik
            $table->integer('harga');       // Harga per kg
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_sampahs');
    }
};
