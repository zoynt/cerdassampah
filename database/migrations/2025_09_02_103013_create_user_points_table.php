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
        // NOTE: Anda sebelumnya memakai nama tabel 'user_points'
        // Saya pertahankan agar tidak memutus kode lain.
        Schema::create('user_points', function (Blueprint $table) {
            $table->id();

            // 1 user = 1 row (UNIQUE)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->unique();

            // poin tidak boleh negatif → unsigned
            $table->unsignedInteger('points')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_points');
    }
};
