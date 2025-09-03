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
            ['quest_name' => 'Scan Sampah Baterai', 'waste_types_id' => 1, 'quest_description' => 'Ayo Scan Sampah Baterai', 'quest_type' => 'scan', 'quest_points' => 10],
            ['quest_name' => 'Scan Sampah Organik', 'waste_types_id' => 2, 'quest_description' => 'Ayo Scan Sampah Organik', 'quest_type' => 'scan', 'quest_points' => 10],
            ['quest_name' => 'Scan Sampah Kaca', 'waste_types_id' => 3, 'quest_description' => 'Ayo Scan Sampah Kaca', 'quest_type' => 'scan', 'quest_points' => 10],
            ['quest_name' => 'Scan Sampah Logam', 'waste_types_id' => 4, 'quest_description' => 'Ayo Scan Sampah Logam', 'quest_type' => 'scan', 'quest_points' => 10],
            ['quest_name' => 'Scan Sampah Kertas', 'waste_types_id' => 5, 'quest_description' => 'Ayo Scan Sampah Kertas', 'quest_type' => 'scan', 'quest_points' => 10],
            ['quest_name' => 'Scan Sampah Plastik', 'waste_types_id' => 6, 'quest_description' => 'Ayo Scan Sampah Plastik', 'quest_type' => 'scan', 'quest_points' => 10],

            ['quest_name' => 'Game Pilah Sampah', 'waste_types_id' => null, 'quest_description' => 'Ayo mainkan Game Pilah Sampah', 'quest_type' => 'game', 'quest_points' => 0],
        ]);
    }
}
