<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bank; // Menggunakan model untuk create() agar lebih aman

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('banks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $allDays = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"];
        $now = Carbon::now();

        $banks = [
            [
                // 'user_id' => 1, // Opsional: Tetapkan ID pengelola jika perlu
                'bank_name' => 'Bank Banjarmasin Utara',
                'slug' => 'bank-banjarmasin-utara',
                'longitude' => '114.5912',
                'latitude' => '-3.3835',
                'address' => 'Alamat Bank Banjarmasin Utara',
                'district' => 'banjarmasin utara',
                'sub_district' => 'Sungai Andai', // Tambahkan kelurahan
                'operational_days' => json_encode($allDays),
                'opening_hour' => '08:00:00',
                'closing_hour' => '16:00:00',
                'description' => 'Bank yang terletak di Banjarmasin Utara.',
                'phone_number' => '1234567890',
                'image_path' => 'storage/bank/tps.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'user_id' => 2,
                'bank_name' => 'Bank Banjarmasin Selatan',
                'slug' => 'bank-banjarmasin-selatan',
                'longitude' => '114.5863',
                'latitude' => '-3.4672',
                'address' => 'Alamat Bank Banjarmasin Selatan',
                'district' => 'banjarmasin selatan',
                'sub_district' => 'Kelayan', // Tambahkan kelurahan
                'operational_days' => json_encode($allDays),
                'opening_hour' => '08:30:00',
                'closing_hour' => '17:00:00',
                'description' => 'Bank yang terletak di Banjarmasin Selatan.',
                'phone_number' => '1234567890',
                'image_path' => 'img/bank/tps.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'user_id' => 3,
                'bank_name' => 'Bank Banjarmasin Tengah',
                'slug' => 'bank-banjarmasin-tengah',
                'longitude' => '114.5995',
                'latitude' => '-3.3334',
                'address' => 'Alamat Bank Banjarmasin Tengah',
                'district' => 'banjarmasin tengah',
                'sub_district' => 'Kertak Baru Ilir', // Tambahkan kelurahan
                'operational_days' => json_encode($allDays),
                'opening_hour' => '09:00:00',
                'closing_hour' => '17:30:00',
                'description' => 'Bank yang terletak di Banjarmasin Tengah.',
                'phone_number' => '1234567890',
                'image_path' => 'storage/bank/tps.jpg', // Menambahkan gambar default
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'user_id' => 4,
                'bank_name' => 'Bank Banjarmasin Barat',
                'slug' => 'bank-banjarmasin-barat',
                'longitude' => '114.5632',
                'latitude' => '-3.3921',
                'address' => 'Alamat Bank Banjarmasin Barat',
                'district' => 'banjarmasin barat',
                'sub_district' => 'Teluk Tiram', // Tambahkan kelurahan
                'operational_days' => json_encode($allDays),
                'opening_hour' => '08:00:00',
                'closing_hour' => '15:30:00',
                'description' => 'Bank yang terletak di Banjarmasin Barat.',
                'phone_number' => '1234567890',
                'image_path' => 'storage/bank/tps.jpg', // Menambahkan gambar default
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                // 'user_id' => 5,
                'bank_name' => 'Bank Banjarmasin Timur',
                'slug' => 'bank-banjarmasin-timur',
                'longitude' => '114.5878',
                'latitude' => '-3.4455',
                'address' => 'Alamat Bank Banjarmasin Timur',
                'district' => 'banjarmasin timur',
                'sub_district' => 'Sungai Lulut', // Tambahkan kelurahan
                'operational_days' => json_encode($allDays),
                'opening_hour' => '08:30:00',
                'closing_hour' => '16:30:00',
                'description' => 'Bank yang terletak di Banjarmasin Timur.',
                'phone_number' => '1234567890',
                'image_path' => 'storage/bank/tps.jpg',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];

        // Menggunakan insert() lebih cepat untuk seeder, tapi pastikan $fillable di Model Bank sudah benar
        // Jika ragu, gunakan loop foreach seperti di bawah:
        foreach ($banks as $bankData) {
            Bank::create($bankData);
        }
        
        // Atau jika Anda yakin semua data bersih, DB::table('banks')->insert($banks); lebih cepat
        // DB::table('banks')->insert($banks); 
    }
}