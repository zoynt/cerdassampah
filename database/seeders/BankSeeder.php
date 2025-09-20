<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $banks = [
                [
                    'bank_name'       => 'Bank Banjarmasin Utara',
                    'bank_longitude'  => '114.5912',
                    'bank_latitude'   => '-3.3835',
                    'alamat'    => 'Alamat Bank Banjarmasin Utara',
                    'kecamatan'       => 'banjarmasin utara',
                    'bank_day'        => json_encode(["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"]),
                    'bank_start_time' => '08:00:00',
                    'bank_end_time'   => '16:00:00',
                    'bank_description'=> 'Bank yang terletak di Banjarmasin Utara.',
                    'bank_no'         => '1234567890', // Pastikan kolom ini ADA di database
                    'image'           => 'storage/bank/tps.jpg', // ← Pakai storage, bukan public/
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ],            
                [
                'bank_name' => 'Bank Banjarmasin Selatan',
                'bank_longitude' => '114.5863',
                'bank_latitude' => '-3.4672',
                'alamat' => 'Alamat Bank Banjarmasin Selatan',
                'kecamatan' => 'banjarmasin selatan',
                'bank_day'        => json_encode(["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"]),
                'bank_start_time' => '08:30:00',
                'bank_end_time' => '17:00:00',
                'bank_description' => 'Bank yang terletak di Banjarmasin Selatan.',
                'bank_no' => '1234567890',
                'image' => 'img/bank/tps.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bank_name' => 'Bank Banjarmasin Tengah',
                'bank_longitude' => '114.5995',
                'bank_latitude' => '-3.3334',
                'alamat' => 'Alamat Bank Banjarmasin Tengah',
                'kecamatan' => 'banjarmasin tengah',
                'bank_day'        => json_encode(["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"]),
                'bank_start_time' => '09:00:00',
                'bank_end_time' => '17:30:00',
                'bank_no' => '1234567890',
                'bank_description' => 'Bank yang terletak di Banjarmasin Tengah.',
                // 'image' => 'img/bank/tps.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bank_name' => 'Bank Banjarmasin Barat',
                'bank_longitude' => '114.5632',
                'bank_latitude' => '-3.3921',
                'alamat' => 'Alamat Bank Banjarmasin Barat',
                'kecamatan' => 'banjarmasin barat',
                'bank_day'        => json_encode(["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"]),
                'bank_start_time' => '08:00:00',
                'bank_end_time' => '15:30:00',
                'bank_no' => '1234567890',
                'bank_description' => 'Bank yang terletak di Banjarmasin Barat.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bank_name' => 'Bank Banjarmasin Timur',
                'bank_longitude' => '114.5878',
                'bank_latitude' => '-3.4455',
                'alamat' => 'Alamat Bank Banjarmasin Timur',
                'kecamatan' => 'banjarmasin timur',
                'bank_day'  => json_encode(["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"]),
                'bank_start_time' => '08:30:00',
                'bank_end_time' => '16:30:00',
                'bank_no' => '1234567890',
                'bank_description' => 'Bank yang terletak di Banjarmasin Timur.',
                'image' => 'storage/bank/tps.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];
        
        // Set default image jika null
        foreach ($banks as &$bank) {
            if (empty($bank['image'])) {
                $bank['image'] = 'storage/bank/tps.jpg';
            }
        }

        DB::table('bank_sampahs')->insert($banks);
    }
}
