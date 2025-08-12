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
            ['type_name' => 'baterai', 'waste_description' => 'Baterai bekas dan tidak terpakai'],
            ['type_name' => 'organik', 'waste_description' => 'Sisa makanan dan limbah organik lainnya'],
            ['type_name' => 'kaca', 'waste_description' => 'Botol dan pecahan kaca'],
            ['type_name' => 'logam', 'waste_description' => 'Kaleng dan limbah logam lainnya'],
            ['type_name' => 'kertas', 'waste_description' => 'Kertas bekas dan limbah kertas lainnya'],
            ['type_name' => 'plastik', 'waste_description' => 'Sampah plastik dan kemasan plastik'],
            ['type_name' => 'residu', 'waste_description' => 'Sisa-sisa limbah yang tidak dapat didaur ulang'],
        ];

        DB::table('waste_types')->insert($wasteTypes);
    }
}
