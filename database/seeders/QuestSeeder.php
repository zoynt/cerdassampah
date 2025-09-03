<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quests')->insert([
            //scan
            ['waste_types_id' => 1, 'quest_description' => 'Ayo Scan Sampah Baterai', 'quest_type' => 'scan'],
            ['waste_types_id' => 2, 'quest_description' => 'Ayo Scan Sampah Organik', 'quest_type' => 'scan'],
            ['waste_types_id' => 3, 'quest_description' => 'Ayo Scan Sampah Kaca', 'quest_type' => 'scan'],
            ['waste_types_id' => 4, 'quest_description' => 'Ayo Scan Sampah Logam', 'quest_type' => 'scan'],
            ['waste_types_id' => 5, 'quest_description' => 'Ayo Scan Sampah Kertas', 'quest_type' => 'scan'],
            ['waste_types_id' => 6, 'quest_description' => 'Ayo Scan Sampah Plastik', 'quest_type' => 'scan'],
        ]);
    }
}
