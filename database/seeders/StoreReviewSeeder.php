<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\StoreReview;

class StoreReviewSeeder extends Seeder
{
    public function run()
    {
        StoreReview::insert([
            // Contoh ulasan untuk store_id 1
            ['store_id' => 1, 'user_id' => 1, 'order_id' => 1, 'rating' => 5, 'review' => 'Barang bagus, pengiriman cepat!', 'created_at' => now(), 'updated_at' => now()],
            ['store_id' => 1, 'user_id' => 2, 'order_id' => 2, 'rating' => 5, 'review' => 'Sesuai deskripsi. Sangat direkomendasikan.', 'created_at' => now(), 'updated_at' => now()],
            ['store_id' => 1, 'user_id' => 3, 'order_id' => 3, 'rating' => 4, 'review' => 'Lumayan, ada sedikit cacat tapi tidak masalah.', 'created_at' => now(), 'updated_at' => now()],

            // Contoh ulasan untuk store_id 2
            ['store_id' => 2, 'user_id' => 4, 'order_id' => 4, 'rating' => 5, 'review' => 'Produk berkualitas tinggi, penjual responsif.', 'created_at' => now(), 'updated_at' => now()],
            ['store_id' => 2, 'user_id' => 5, 'order_id' => 5, 'rating' => 5, 'review' => 'Mantap! Bakal beli lagi di sini.', 'created_at' => now(), 'updated_at' => now()],

            // Contoh ulasan untuk store_id 3
            ['store_id' => 3, 'user_id' => 6, 'order_id' => 6, 'rating' => 4, 'review' => 'Harganya oke, kualitas sesuai.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}