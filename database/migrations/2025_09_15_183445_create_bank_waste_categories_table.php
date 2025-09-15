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
        Schema::create('bank_waste_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade');
            $table->foreignId('waste_category_id')->constrained('waste_categories')->onDelete('cascade');
            $table->decimal('price_per_kg', 10, 2)->default(0.00); // 10 total digit, 2 di belakang koma
            $table->unique(['bank_id', 'waste_category_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_waste_categories');
    }
};
