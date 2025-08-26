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
        $locations = [
            ['tps_name' => 'TPS Sundai', 'tps_longitude' => '123.456', 'tps_latitude' => '-123.456', 'tps_status' => 'legal', 'tps_description' => 'Deskripsi TPS', 'kecamatan' => 'banjarmasin utara'],
        ];

        DB::table('tps')->insert($locations);
    }
}
