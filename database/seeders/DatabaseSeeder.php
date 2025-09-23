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
        // User::factory(10)->create();
        $this->call([
            WasteTypeSeeder::class,
            RolePermissionSeeder::class,
            TpsSeeder::class,
            SurungSeeder::class,
            BankSeeder::class,
            MaterialSeeder::class,
            QuestSeeder::class,
            RekeningBankSampahUserSeeder::class,
            BankTransactionSeeder::class,
            // Add other seeders here as needed
        ]);

    }
}
