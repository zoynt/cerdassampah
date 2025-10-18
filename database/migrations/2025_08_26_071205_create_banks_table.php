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
            
            // [PERBAIKAN] Menambahkan foreign key user_id untuk pengelola
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->string('bank_name');
            $table->string('slug')->unique();

            // [PERBAIKAN] Mengubah nama kolom agar sesuai dengan form & controller
            $table->text('address'); // <-- Menggantikan 'alamat'
            $table->string('district'); // <-- Menggantikan 'kecamatan' (Enum tidak fleksibel)
            $table->string('sub_district')->nullable(); // <-- Menggantikan 'kelurahan'
            
            // [PERBAIKAN] Menggunakan tipe data decimal untuk presisi
            $table->decimal('latitude', 10, 8)->nullable(); // <-- Menggantikan 'bank_latitude'
            $table->decimal('longitude', 11, 8)->nullable(); // <-- Menggantikan 'bank_longitude'
            
            $table->json('operational_days')->nullable(); // <-- Menggantikan 'bank_day'
            $table->time('opening_hour')->nullable(); // <-- Menggantikan 'bank_start_time'
            $table->time('closing_hour')->nullable(); // <-- Menggantikan 'bank_end_time'
            
            $table->string('phone_number', 25)->nullable(); // <-- Menggantikan 'bank_no' (tipe text)
            $table->text('description')->nullable(); // <-- Menggantikan 'bank_description'
            $table->string('image_path')->nullable(); // <-- Menggantikan 'image'
            
            // [PERBAIKAN] Menambahkan kolom status
            $table->boolean('is_active')->default(true);
            
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