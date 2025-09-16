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
        Schema::table('bank_sampahs', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('alamat');
            $table->string('jam_operasional')->nullable()->after('deskripsi');
            $table->string('kontak_person')->nullable()->after('jam_operasional');
            $table->string('nomor_telepon')->nullable()->after('kontak_person');
            $table->string('latitude')->nullable()->after('nomor_telepon');
            $table->string('longitude')->nullable()->after('latitude');
            $table->string('image_url')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_sampahs');
    }
};
