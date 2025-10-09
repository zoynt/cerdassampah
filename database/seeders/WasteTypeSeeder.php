<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wasteTypes = [
            [
                'type_name' => 'Organik',
                'waste_description' => 'Sampah yang berasal dari sisa makhluk hidup dan mudah terurai secara alami, seperti sisa makanan, daun, dan ranting.'
            ],
            [
                'type_name' => 'Anorganik',
                'waste_description' => 'Sampah yang sulit terurai dan dapat didaur ulang, seperti plastik, kertas, kaca, dan logam.'
            ],
            [
                'type_name' => 'Residu',
                'waste_description' => 'Sampah sisa yang tidak dapat didaur ulang maupun dikomposkan, serta limbah B3 (Bahan Berbahaya dan Beracun).'
            ],
        ];

        DB::table('waste_types')->insert($wasteTypes);
    }
}