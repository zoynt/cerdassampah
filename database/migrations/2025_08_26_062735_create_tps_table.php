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
        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->string('tps_name');
            $table->string('tps_longitude');
            $table->string('tps_latitude');
            $table->string('tps_address');
            $table->enum('tps_status', ['resmi', 'liar'])->nullable();
            $table->enum('kecamatan', [
                'banjarmasin utara',
                'banjarmasin selatan',
                'banjarmasin tengah',
                'banjarmasin barat',
                'banjarmasin timur'
            ])->nullable();
            $table->json('tps_day');
            $table->time('tps_start_time');
            $table->time('tps_end_time');
            $table->string('tps_transport');
            $table->text('tps_description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tps');
    }
};
