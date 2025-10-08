<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call([
            // ==========================================================
            // URUTAN YANG BENAR:
            // ==========================================================

            // 1. Data Master (Tidak memiliki ketergantungan atau hanya ke user/role)
            // Ini adalah data dasar yang harus ada terlebih dahulu.
            RolePermissionSeeder::class, // Biasanya ini yang pertama, karena membuat user & role
            BankSeeder::class,
            BankWasteCategorySeeder::class,
            TpsSeeder::class,
            WasteTypeSeeder::class,
            SurungSeeder::class,
            MaterialSeeder::class,
            QuestSeeder::class,
            ProductCategorySeeder::class,
            StoreSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class, 
            ProductImageSeeder::class,
            StoreReviewSeeder::class,
            OrderItemSeeder::class,
            MySalesHistorySeeder::class,
        ]);

            // 2. Data Relasi (Bergantung pada data master di atas)
            RekeningBankSampahUserSeeder::class, // Bergantung pada User dan Bank
            BankWasteProductSeeder::class,       // Bergantung pada Bank dan BankWasteCategory

            // 3. Data Transaksi (Bergantung pada data relasi)
            BankTransactionSeeder::class,        // Bergantung pada RekeningBankSampahUser

            // 4. Detail Transaksi (Paling akhir, bergantung pada transaksi & produk)
            BankTransactionDetailSeeder::class,  // Bergantung pada BankTransaction dan BankWasteProduct
        ]);
    }
}
