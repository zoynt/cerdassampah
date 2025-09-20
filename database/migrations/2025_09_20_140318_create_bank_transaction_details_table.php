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
            $table->foreignId('bank_waste_category_id')->constrained('bank_waste_categories')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('bank_transactions')->onDelete('cascade');
            $table->decimal('weight_kg', 10, 2)->default(0.00);
            $table->decimal('price_per_kg', 10, 2)->default(0.00);
            $table->string('detail_description');
            $table->decimal('detail_amount', 10, 2)->default(0.00);
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
