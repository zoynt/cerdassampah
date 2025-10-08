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
            // === SESUAIKAN DENGAN 3 JENIS SAMPAH BARU ===
            [
                'quest_name' => 'Scan Sampah Organik',
                'waste_types_id' => 1, // ID untuk Organik
                'quest_description' => 'Ayo Scan Sampah Organik',
                'quest_type' => 'scan',
                'quest_points' => 10
            ],
            [
                'quest_name' => 'Scan Sampah Anorganik',
                'waste_types_id' => 2, // ID untuk Anorganik
                'quest_description' => 'Ayo Scan Sampah Anorganik',
                'quest_type' => 'scan',
                'quest_points' => 10
            ],
            [
                'quest_name' => 'Scan Sampah Residu',
                'waste_types_id' => 3, // ID untuk Residu
                'quest_description' => 'Ayo Scan Sampah Residu',
                'quest_type' => 'scan',
                'quest_points' => 10
            ],

            // === QUEST LAIN (TIDAK TERIKAT JENIS SAMPAH) ===
            [
                'quest_name' => 'Game Pilah Sampah',
                'waste_types_id' => null,
                'quest_description' => 'Ayo mainkan Game Pilah Sampah',
                'quest_type' => 'game',
                'quest_points' => 0
            ],
        ]);
    }
}
