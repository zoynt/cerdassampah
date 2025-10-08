<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        Store::insert([
            [
                'user_id' => 1,
                'name' => 'Toko Daur Ulang',
                'slug' => 'toko-daur-ulang',
                'description' => 'Toko khusus menjual produk daur ulang.',
                'phone_number' => '081234567890',
                'is_active' => true,
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'address' => 'Jakarta, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'Hijau Market',
                'slug' => 'hijau-market',
                'description' => 'Menyediakan berbagai bahan daur ulang dari plastik dan kertas.',
                'phone_number' => '082345678901',
                'is_active' => true,
                'latitude' => '-6.2100',
                'longitude' => '106.8500',
                'address' => 'Bekasi, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => 'Kreasi Bekas',
                'slug' => 'kreasi-bekas',
                'description' => 'Sedia barang bekas layak daur ulang.',
                'phone_number' => '083456789012',
                'is_active' => true,
                'latitude' => '-6.2112',
                'longitude' => '106.8544',
                'address' => 'Depok, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}