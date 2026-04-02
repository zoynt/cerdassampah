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
        Schema::create('bank_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('bank_transactions')->onDelete('cascade');
            $table->foreignId('bank_waste_product_id')->constrained('bank_waste_products')->onDelete('cascade');
            $table->decimal('weight_kg', 8, 2); // Berat (misal: 10.50 kg)
            $table->decimal('price_per_kg', 15, 2); // Harga "snapshot" saat transaksi
            $table->decimal('subtotal', 15, 2); // Total (berat x harga)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transaction_details');
    }
};
