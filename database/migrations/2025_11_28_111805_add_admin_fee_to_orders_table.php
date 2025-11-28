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
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom fee dan bersih, default 0 agar data lama aman
            $table->decimal('admin_fee', 15, 2)->default(0)->after('total_amount');
            $table->decimal('net_amount', 15, 2)->default(0)->after('admin_fee');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['admin_fee', 'net_amount']);
        });
    }
};
