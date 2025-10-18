<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();

        // Mendapatkan ID kategori dari database berdasarkan slug
        $kertasId = DB::table('product_categories')->where('slug', 'kertas')->value('id');
        $plastikId = DB::table('product_categories')->where('slug', 'plastik')->value('id');
        $logamId = DB::table('product_categories')->where('slug', 'logam')->value('id');

        // Mendapatkan ID toko dari database berdasarkan nama (BENAR)
        $tokoDaurUlangId = DB::table('stores')->where('name', 'Toko Daur Ulang Jaya')->value('id');
        $hijauMarketId = DB::table('stores')->where('name', 'Hijau Market Banjarmasin')->value('id');
        $kreasiBekasId = DB::table('stores')->where('name', 'Kreasi Bekas Banjar')->value('id');

        Product::insert([
            // Produk Awal
            [
                'store_id' => $tokoDaurUlangId,
                'product_category_id' => $kertasId,
                'name' => 'Kardus Tebal',
                'price' => 2000,
                'stock' => 50,
                'selling_unit' => 'kg',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Kardus tebal bekas dalam kondisi baik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $hijauMarketId,
                'product_category_id' => $kertasId,
                'name' => 'Kertas HVS Bekas',
                'price' => 1500,
                'stock' => 100,
                'selling_unit' => 'kg',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Kertas HVS bekas, cocok untuk daur ulang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $tokoDaurUlangId,
                'product_category_id' => $plastikId,
                'name' => 'Botol Air Mineral',
                'price' => 2500,
                'stock' => 200,
                'selling_unit' => 'item',
                'weight_per_item' => 0.05,
                'status' => 'available',
                'description' => 'Botol plastik bekas berbagai merek.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $kreasiBekasId,
                'product_category_id' => $logamId,
                'name' => 'Kaleng Sarden',
                'price' => 2500,
                'stock' => 80,
                'selling_unit' => 'item',
                'weight_per_item' => 0.1,
                'status' => 'available',
                'description' => 'Kaleng sarden bekas, sudah dibersihkan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Tambahan 10 Produk
            [
                'store_id' => $hijauMarketId,
                'product_category_id' => $kertasId,
                'name' => 'Buku Tulis Bekas',
                'price' => 1200,
                'stock' => 50,
                'selling_unit' => 'kg',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Buku tulis bekas layak daur ulang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $kreasiBekasId,
                'product_category_id' => $plastikId,
                'name' => 'Gelas Plastik',
                'price' => 1000,
                'stock' => 320,
                'selling_unit' => 'item',
                'weight_per_item' => 0.02,
                'status' => 'available',
                'description' => 'Gelas plastik bekas, siap olah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $tokoDaurUlangId,
                'product_category_id' => $logamId,
                'name' => 'Seng Bekas',
                'price' => 7000,
                'stock' => 60,
                'selling_unit' => 'kg',
                'weight_per_item' => 5,
                'status' => 'available',
                'description' => 'Lembaran seng bekas, cocok untuk kerajinan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $hijauMarketId,
                'product_category_id' => $kertasId,
                'name' => 'Koran Lama',
                'price' => 1000,
                'stock' => 88,
                'selling_unit' => 'kg',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Tumpukan koran bekas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $tokoDaurUlangId,
                'product_category_id' => $plastikId,
                'name' => 'Tutup Botol',
                'price' => 500,
                'stock' => 500,
                'selling_unit' => 'item',
                'weight_per_item' => 0.01,
                'status' => 'available',
                'description' => 'Berbagai macam tutup botol plastik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $kreasiBekasId,
                'product_category_id' => $logamId,
                'name' => 'Ember Pecah',
                'price' => 3000,
                'stock' => 40,
                'selling_unit' => 'item',
                'weight_per_item' => 0.5,
                'status' => 'available',
                'description' => 'Ember plastik pecah, bisa dilebur ulang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $kreasiBekasId,
                'product_category_id' => $kertasId,
                'name' => 'Majalah Bekas',
                'price' => 1300,
                'stock' => 75,
                'selling_unit' => 'kg',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Tumpukan majalah bekas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $hijauMarketId,
                'product_category_id' => $plastikId,
                'name' => 'Jerigen Plastik',
                'price' => 5000,
                'stock' => 95,
                'selling_unit' => 'item',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Jerigen bekas bahan HDPE.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $tokoDaurUlangId,
                'product_category_id' => $logamId,
                'name' => 'Paku Karatan',
                'price' => 1000,
                'stock' => 30,
                'selling_unit' => 'kg',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Paku karatan siap dilebur.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => $tokoDaurUlangId,
                'product_category_id' => $kertasId,
                'name' => 'Karton Box',
                'price' => 2200,
                'stock' => 112,
                'selling_unit' => 'kg',
                'weight_per_item' => 1,
                'status' => 'available',
                'description' => 'Karton box tebal bekas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}