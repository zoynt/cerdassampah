<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationAndScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Buat Lokasi 1 dan langsung buat jadwalnya
        $BankZahra = Location::create([
            'location_name' => 'Bank Sampah Zahra',
            'location_latitude' => '-3.316817',
            'location_longitude' => '114.590217',
            'description' => 'Bank sampah sungai andai',
            'location_type' => 'Bank Sampah',
            'kecamatan' => 'banjarmasin utara'
        ]);
        Schedule::create([
            'locations_id' => $BankZahra->id,
            'day' => 'Setiap Hari',
            'start_time' => '12:00:00',
            'end_time' => '16:00:00'
        ]);

        // Buat Lokasi 2 dan langsung buat jadwalnya
        // $bankSampah = Location::create([
        //     'name' => 'Bank Sampah Sekumpul',
        //     'latitude' => '-3.428570',
        //     'longitude' => '114.839840',
        //     'description' => 'Menerima setoran sampah anorganik dari warga.',
        //     'location_type' => 'Bank Sampah'
        // ]);
        // Schedule::create([
        //     'location_id' => $bankSampah->id,
        //     'day' => 'Senin - Jumat',
        //     'start_time' => '09:00:00',
        //     'end_time' => '15:00:00'
        // ]);
    }
}
