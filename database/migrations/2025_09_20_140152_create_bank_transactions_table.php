<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekening_id')->constrained('rekening_bank_sampah_users')->onDelete('cascade');
            $table->string('transaction_code')->unique()->nullable(); // Dibuat nullable jika ada kasus transaksi tanpa kode unik

            // PERBAIKAN: Menambahkan kolom yang dibutuhkan oleh controller
            $table->string('description'); // Untuk deskripsi (misal: "Setoran Sampah", "Penarikan Tunai")
            $table->enum('transaction_type', ['pemasukan', 'penarikan']); // Untuk jenis transaksi

            $table->decimal('transaction_amount', 15, 2)->default(0.00); // Diperbesar untuk menampung nilai yang lebih besar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
