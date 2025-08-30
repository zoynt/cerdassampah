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
        Schema::create('surungs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tps_id')->constrained('tps')->onDelete('cascade');
            $table->string('surung_name');
            $table->string('surung_longitude');
            $table->string('surung_latitude');
            $table->enum('kecamatan', [
                'banjarmasin utara',
                'banjarmasin selatan',
                'banjarmasin tengah',
                'banjarmasin barat',
                'banjarmasin timur'
            ]);
            $table->string('worker_name');
            $table->string('area');
            $table->json('surung_day');
            $table->time('surung_start_time');
            $table->time('surung_end_time');
            $table->text('surung_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surungs');
    }
};
