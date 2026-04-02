<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            
            $table->string('image_path')->nullable()->after('address');
            $table->string('district')->nullable()->after('image_path');      
            $table->string('sub_district')->nullable()->after('district');   
            $table->json('operational_days')->nullable()->after('sub_district'); 
            $table->time('opening_hour')->nullable()->after('operational_days');  
            $table->time('closing_hour')->nullable()->after('opening_hour'); 
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Hapus kolom dengan nama barunya
            $table->dropColumn([
                'image_path',
                'district',
                'sub_district',
                'operational_days',
                'opening_hour',
                'closing_hour'
            ]);
        });
    }
};