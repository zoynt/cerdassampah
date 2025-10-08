<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            // Tambahkan kolom nomor telepon setelah kelurahan
            $table->string('nomor_telepon')->nullable()->after('kelurahan');
        });
    }

    public function down(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropColumn('nomor_telepon');
        });
    }
};