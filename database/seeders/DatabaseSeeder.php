<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Exports\MySalesHistorySeeder;

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
            
            //MySalesHistorySeeder::class,
             RekeningBankSampahUserSeeder::class, // Bergantung pada User dan Bank
            BankWasteProductSeeder::class,       // Bergantung pada Bank dan BankWasteCategory

            BankTransactionSeeder::class,        // Bergantung pada RekeningBankSampahUser

            BankTransactionDetailSeeder::class,  // Bergantung pada BankTransaction dan BankWasteProduct
        ]);

            // 2. Data Relasi (Bergantung pada data master di atas
    }
}
