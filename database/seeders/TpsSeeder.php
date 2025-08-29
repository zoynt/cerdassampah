<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            'banjarmasin utara',
            'banjarmasin selatan',
            'banjarmasin tengah',
            'banjarmasin barat',
            'banjarmasin timur'
        ];

        $statuses = ['resmi', 'liar'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('tps')->insert([
                'tps_name' => 'TPS ' . $i,
                'tps_longitude' => '114.5' . rand(1000, 9999),
                'tps_latitude' => '-3.3' . rand(1000, 9999),
                'tps_address' => 'Alamat TPS ' . $i,
                'tps_status' => $statuses[array_rand($statuses)],
                'tps_description' => 'Tempat Penampungan Sampah sementara ke-' . $i,
                'kecamatan' => $kecamatans[array_rand($kecamatans)],
                'tps_day' => 'Senin - Jumat',
                'tps_start_time' => '07:00:00',
                'tps_end_time' => '17:00:00',
                'tps_transport' => 'Truk Sampah',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
