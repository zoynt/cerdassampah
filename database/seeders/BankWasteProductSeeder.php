<?php

namespace Database\Seeders;

use App\Models\BankWasteProduct;
use App\Models\BankWasteCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankWasteProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BankWasteProduct::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil ID dari kategori yang sudah ada untuk relasi
        $categories = BankWasteCategory::pluck('id', 'name');

        // Daftar produk sampah yang akan di-seed
        // Anda bisa menambahkan atau mengubah data ini sesuai kebutuhan
        $products = [
            // Bank ID 1 (Contoh: Bank Sampah KBU)
            ['bank_id' => 1, 'category_name' => 'Plastik', 'item_name' => 'Botol PET Bening (Aqua, dll)', 'price_per_kg' => 3500],
            ['bank_id' => 1, 'category_name' => 'Plastik', 'item_name' => 'Gelas Plastik Bersih', 'price_per_kg' => 2500],
            ['bank_id' => 1, 'category_name' => 'Kertas & Kardus', 'item_name' => 'Kardus Kering', 'price_per_kg' => 1800],
            ['bank_id' => 1, 'category_name' => 'Kertas & Kardus', 'item_name' => 'Kertas HVS/Buku', 'price_per_kg' => 1500],
            ['bank_id' => 1, 'category_name' => 'Logam', 'item_name' => 'Kaleng Aluminium (Minuman)', 'price_per_kg' => 9000],

            // Bank ID 2 (Contoh: Bank Sampah Induk)
            ['bank_id' => 2, 'category_name' => 'Plastik', 'item_name' => 'Botol PET Bening', 'price_per_kg' => 3800],
            ['bank_id' => 2, 'category_name' => 'Kertas & Kardus', 'item_name' => 'Kardus Kering & Bersih', 'price_per_kg' => 2000],
            ['bank_id' => 2, 'category_name' => 'Kaca', 'item_name' => 'Botol Kaca Bening', 'price_per_kg' => 500],
            ['bank_id' => 2, 'category_name' => 'Kaca', 'item_name' => 'Botol Kaca Berwarna', 'price_per_kg' => 300],
            ['bank_id' => 2, 'category_name' => 'Logam', 'item_name' => 'Besi Bekas Campur', 'price_per_kg' => 4000],

            // Bank ID 3 (Contoh: Bank Sampah Sekumpul)
            ['bank_id' => 3, 'category_name' => 'Plastik', 'item_name' => 'Kresek & Plastik Lembaran', 'price_per_kg' => 800],
            ['bank_id' => 3, 'category_name' => 'Kertas & Kardus', 'item_name' => 'Koran Bekas', 'price_per_kg' => 1200],
            ['bank_id' => 3, 'category_name' => 'Logam', 'item_name' => 'Kaleng Seng (Susu, Sarden)', 'price_per_kg' => 1500],
            ['bank_id' => 3, 'category_name' => 'Elektronik', 'item_name' => 'Kabel Tembaga', 'price_per_kg' => 75000],
        ];

        foreach ($products as $productData) {
            // Cari ID kategori berdasarkan nama
            $categoryId = $categories[$productData['category_name']] ?? null;

            if ($categoryId) {
                BankWasteProduct::create([
                    'bank_id' => $productData['bank_id'],
                    'waste_category_id' => $categoryId,
                    'item_name' => $productData['item_name'],
                    'price_per_kg' => $productData['price_per_kg'],
                ]);
            }
        }
    }
}
