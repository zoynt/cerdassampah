<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // Hapus data lama agar tidak duplikat
        Store::query()->delete();

        $stores = [
            [
                'user_id' => 1,
                'name' => 'Toko Daur Ulang Jaya',
                'description' => 'Toko khusus menjual produk daur ulang berkualitas.',
                'phone_number' => '081234567890',
                'is_active' => true,
                'latitude' => '-3.319088',
                'longitude' => '114.590214',
                'address' => 'Jl. A. Yani Km 2, Banjarmasin',
                'district' => 'Banjarmasin Tengah',
                'sub_district' => 'Sungai Baru',
                'operational_days' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'], // Buka hari ini
                'opening_hour' => '08:00',
                'closing_hour' => '17:00',
            ],
            [
                'user_id' => 2,
                'name' => 'Hijau Market Banjarmasin',
                'description' => 'Menyediakan berbagai bahan daur ulang dari plastik dan kertas.',
                'phone_number' => '082345678901',
                'is_active' => true,
                'latitude' => '-3.324555',
                'longitude' => '114.594111',
                'address' => 'Jl. Veteran No. 10, Banjarmasin',
                'district' => 'Banjarmasin Timur',
                'sub_district' => 'Kuripan',
                'operational_days' => ['Senin', 'Selasa', 'Rabu', 'Jumat', 'Sabtu'], 
                'opening_hour' => '09:00',
                'closing_hour' => '16:00',
            ],
            [
                'user_id' => 3,
                'name' => 'Kreasi Bekas Banjar',
                'description' => 'Sedia barang bekas layak daur ulang, terutama logam dan kaca.',
                'phone_number' => '083456789012',
                'is_active' => true,
                'latitude' => '-3.298765',
                'longitude' => '114.588888',
                'address' => 'Jl. Sultan Adam, Banjarmasin',
                'district' => 'Banjarmasin Utara',
                'sub_district' => 'Sultan Adam',
                'operational_days' => ['Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], // Buka hari ini
                'opening_hour' => '10:00',
                'closing_hour' => '18:00',
            ],
        ];

        foreach ($stores as $storeData) {
            // Tambahkan slug secara otomatis
            $storeData['slug'] = Str::slug($storeData['name']);
            Store::create($storeData);
        }
    }
}