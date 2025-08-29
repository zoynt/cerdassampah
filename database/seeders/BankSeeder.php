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
               DB::table('banks')->insert([
            [
                'bank_name' => 'Bank Banjarmasin Utara',
                'bank_longitude' => '114.5912',
                'bank_latitude' => '-3.3835',
                'bank_address' => 'Alamat Bank Banjarmasin Utara',
                'kecamatan' => 'banjarmasin utara',
                'bank_day' => 'Senin',
                'bank_start_time' => '08:00:00',
                'bank_end_time' => '16:00:00',
                'bank_description' => 'Bank yang terletak di Banjarmasin Utara.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bank_name' => 'Bank Banjarmasin Selatan',
                'bank_longitude' => '114.5863',
                'bank_latitude' => '-3.4672',
                'bank_address' => 'Alamat Bank Banjarmasin Selatan',
                'kecamatan' => 'banjarmasin selatan',
                'bank_day' => 'Selasa',
                'bank_start_time' => '08:30:00',
                'bank_end_time' => '17:00:00',
                'bank_description' => 'Bank yang terletak di Banjarmasin Selatan.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bank_name' => 'Bank Banjarmasin Tengah',
                'bank_longitude' => '114.5995',
                'bank_latitude' => '-3.3334',
                'bank_address' => 'Alamat Bank Banjarmasin Tengah',
                'kecamatan' => 'banjarmasin tengah',
                'bank_day' => 'Rabu',
                'bank_start_time' => '09:00:00',
                'bank_end_time' => '17:30:00',
                'bank_description' => 'Bank yang terletak di Banjarmasin Tengah.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bank_name' => 'Bank Banjarmasin Barat',
                'bank_longitude' => '114.5632',
                'bank_latitude' => '-3.3921',
                'bank_address' => 'Alamat Bank Banjarmasin Barat',
                'kecamatan' => 'banjarmasin barat',
                'bank_day' => 'Kamis',
                'bank_start_time' => '08:00:00',
                'bank_end_time' => '15:30:00',
                'bank_description' => 'Bank yang terletak di Banjarmasin Barat.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bank_name' => 'Bank Banjarmasin Timur',
                'bank_longitude' => '114.5878',
                'bank_latitude' => '-3.4455',
                'bank_address' => 'Alamat Bank Banjarmasin Timur',
                'kecamatan' => 'banjarmasin timur',
                'bank_day' => 'Jumat',
                'bank_start_time' => '08:30:00',
                'bank_end_time' => '16:30:00',
                'bank_description' => 'Bank yang terletak di Banjarmasin Timur.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
