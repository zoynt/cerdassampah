<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->string('image')->nullable();
            $table->date('waktu_lapor')->nullable();
            $table->date('waktu_selesai')->nullable();
            $table->timestamps();

            // Jika Anda menggunakan foreign key:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
