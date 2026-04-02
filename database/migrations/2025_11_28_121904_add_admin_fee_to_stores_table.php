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
        Schema::table('stores', function (Blueprint $table) {
            // Menambahkan kolom admin_fee dengan tipe decimal
            // 5,2 artinya bisa menampung angka sampai 999.99
            // Default 5.00 artinya 5%
            $table->decimal('admin_fee', 5, 2)->default(5.00)->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('admin_fee');
        });
    }
};
