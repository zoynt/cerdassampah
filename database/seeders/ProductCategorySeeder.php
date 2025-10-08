<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('product_categories')->delete();

        $categories = [
            [
                'name' => 'Kertas & Kardus',
                'description' => 'Produk daur ulang dari kertas dan kardus.',
                'slug' => 'kertas',
                'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plastik',
                'description' => 'Produk daur ulang dari bahan plastik.',
                'slug' => 'plastik',
                'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6"></path></svg>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Logam',
                'description' => 'Produk daur ulang dari berbagai jenis logam.',
                'slug' => 'logam',
                'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.63 8.37l-5.63-5.63a2.98 2.98 0 00-4.24 0L3.37 10.13a2.98 2.98 0 000 4.24l5.63 5.63a2.98 2.98 0 004.24 0l7.39-7.39a2.98 2.98 0 000-4.24zM10 14l-4-4m4 4l-2-6"></path></svg>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ProductCategory::insert($categories);
    }
}