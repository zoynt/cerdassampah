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
        Schema::table('order_items', function (Blueprint $table) {
            // Mengubah kolom 'quantity' menjadi DECIMAL dengan total 10 digit, 3 di antaranya di belakang koma.
            // Ini memungkinkan angka seperti 0.500 atau 1.250
            $table->decimal('quantity', 10, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Mengembalikan kolom 'quantity' ke tipe INTEGER jika migrasi di-rollback.
            $table->integer('quantity')->change();
        });
    }
};