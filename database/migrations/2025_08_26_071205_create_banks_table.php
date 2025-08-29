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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('bank_longitude');
            $table->string('bank_latitude');
            $table->string('bank_address');
            $table->enum('kecamatan', [
                'banjarmasin utara',
                'banjarmasin selatan',
                'banjarmasin tengah',
                'banjarmasin barat',
                'banjarmasin timur'
            ]);
            $table->string('bank_day');
            $table->time('bank_start_time');
            $table->time('bank_end_time');
            $table->text('bank_no')->nullable();
            $table->text('bank_description')->nullable();
            $table->text('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
