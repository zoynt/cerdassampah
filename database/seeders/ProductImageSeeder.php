<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        DB::table('product_images')->delete();

        // Ambil semua ID produk yang sudah ada
        $productIds = DB::table('products')->pluck('id');

        $images = [];
        foreach ($productIds as $id) {
            $images[] = [
                'product_id' => $id,
                'image_path' => 'img/cat1.jpeg', // Ganti dengan path gambar dummy Anda
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('product_images')->insert($images);
    }
}