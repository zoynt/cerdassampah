<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('kategori');
            $table->unsignedInteger('harga');
            $table->unsignedInteger('stok'); // 'Terjual' kita anggap sebagai 'Stok'
            $table->string('alamat');
            $table->string('satuan_berat')->default('Kilogram');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
