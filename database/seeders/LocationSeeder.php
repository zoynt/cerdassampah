<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $locations = [
            ['location_name' => 'bank sampah zahra', 'location_type' => 'bank sampah', 'kecamatan' => 'banjarmasin utara'],
        ];

        DB::table('locations')->insert($locations);
    }
}
