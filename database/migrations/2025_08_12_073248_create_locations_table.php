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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->enum('location_type', ['tps', 'bank sampah', 'surung sintak']);
            $table->string('location_longitude');
            $table->string('location_latitude');
            $table->enum('location_status', ['legal', 'ilegal'])->nullable();
            $table->text('description')->nullable();
            $table->enum('kecamatan', [
                'banjarmasin utara',
                'banjarmasin selatan',
                'banjarmasin tengah',
                'banjarmasin barat',
                'banjarmasin timur'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
