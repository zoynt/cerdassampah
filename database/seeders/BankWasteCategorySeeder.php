<?php

namespace Database\Seeders;

use App\Models\BankWasteCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankWasteCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data lama untuk menghindari duplikasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BankWasteCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Daftar kategori yang akan dibuat
        $categories = [
            ['name' => 'Plastik', 'description' => 'Semua jenis sampah berbahan dasar plastik.'],
            ['name' => 'Kertas & Kardus', 'description' => 'Termasuk koran, majalah, HVS, dan kardus bekas.'],
            ['name' => 'Logam', 'description' => 'Termasuk besi, aluminium, kaleng, dan sejenisnya.'],
            ['name' => 'Kaca', 'description' => 'Botol atau serpihan kaca bening maupun berwarna.'],
            ['name' => 'Elektronik', 'description' => 'Sampah elektronik bekas seperti kabel, HP, dan komponen lainnya.'],
        ];

        // Looping untuk memasukkan data ke database
        foreach ($categories as $category) {
            BankWasteCategory::create($category);
        }
    }
}
