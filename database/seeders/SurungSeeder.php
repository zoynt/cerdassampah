<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SurungSeeder extends Seeder
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

        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Ambil semua ID TPS yang tersedia
        $tpsIds = DB::table('tps')->pluck('id')->toArray();

        if (empty($tpsIds)) {
            // Jika tidak ada TPS, hentikan seeding
            $this->command->info('Tidak ada data TPS ditemukan. Jalankan TpsSeeder terlebih dahulu.');
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            DB::table('surungs')->insert([
                'tps_id' => $tpsIds[array_rand($tpsIds)],
                'surung_name' => 'Surung ' . $i,
                'surung_longitude' => '114.6' . rand(1000, 9999),
                'surung_latitude' => '-3.4' . rand(1000, 9999),
                'kecamatan' => $kecamatans[array_rand($kecamatans)],
                'worker_name' => 'Petugas ' . $i,
                'area' => 'Area ' . rand(1, 10),
                'surung_day' => json_encode($allDays),
                'surung_start_time' => '06:00:00',
                'surung_end_time' => '14:00:00',
                'surung_description' => 'Surung pengangkut sampah wilayah ' . $kecamatans[array_rand($kecamatans)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
