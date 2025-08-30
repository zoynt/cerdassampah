<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $materials = [
            [
                'waste_types_id' => 1, // Battery
                'description_mat' => 'Baterai bekas mengandung logam berat seperti merkuri, timbal, dan kadmium yang sangat beracun bagi manusia dan lingkungan. Jika dibuang sembarangan, bahan kimianya dapat mencemari tanah dan air.',
                'recycle_info' => 'Komponen logam bisa digunakan kembali untuk baterai baru.'
            ],
            [
                'waste_types_id' => 2, // Organic
                'description_mat' => 'Sisa makanan seperti nasi, lauk, dan makanan olahan lainnya termasuk sampah organik yang mudah membusuk.',
                'recycle_info' => 'Kompos sebagai pupuk alami.'
            ],
            [
                'waste_types_id' => 3, // Glass
                'description_mat' => 'Mendaur ulang satu ton kaca bisa menghemat lebih dari setengah ton pasir yang digunakan dalam pembuatan kaca baru',
                'recycle_info' => 'Potongan botol kaca bekas yang dipotong dan dihaluskan bisa digunakan sebagai gelas minum unik atau tempat lilin artistik yang estetik.'
            ],
            [
                'waste_types_id' => 3, // Glass
                'description_mat' => 'Satu botol kaca yang didaur ulang bisa menghemat energi untuk menyalakan lampu bohlam selama 4 jam',
                'recycle_info' => 'Botol kaca bekas dapat dimanfaatkan sebagai vas bunga dengan menambahkan lukisan tangan atau pita hias di bagian luar untuk mempercantiknya.'
            ],
            [
                'waste_types_id' => 4, // Metal
                'description_mat' => 'Sebuah kaleng aluminium bekas dapat kembali ke rak toko dalam bentuk kaleng baru hanya dalam waktu 60 hari setelah didaur ulang.',
                'recycle_info' => 'Kaleng minuman bekas dapat dicuci bersih dan dimodifikasi menjadi tempat pensil unik dengan tambahan cat atau stiker dekoratif.'
            ],
            [
                'waste_types_id' => 4, // Metal
                'description_mat' => 'Tahukah kamu? besi yang berkarat dapat didaur ulang menjadi produk baru jika diproses dengan benar',
                'recycle_info' => 'Tutup botol logam yang sering diabaikan bisa dirangkai menggunakan kawat menjadi hiasan dinding berbentuk bunga atau simbol tertentu yang artistik.'
            ],
            [
                'waste_types_id' => 5, // Paper
                'description_mat' => 'Limbah kertas yang didaur ulang dapat menghemat hingga 60% energi dibandingkan dengan membuat kertas baru dari bahan mentah.',
                'recycle_info' => 'Kertas koran bekas yang biasanya hanya berakhir di tempat sampah dapat dilipat menjadi kotak penyimpanan kecil untuk barang-barang seperti perhiasan atau kunci.'
            ],
            [
                'waste_types_id' => 5, // Paper
                'description_mat' => 'Tahukah kamu? Limbah kertas yang didaur ulang dapat menghemat hingga 60% energi dibandingkan dengan membuat kertas baru dari bahan mentah.',
                'recycle_info' => 'Buku bekas yang sudah tidak terpakai bisa diubah menjadi karya seni pop-up atau diukir untuk membuat bentuk 3D seperti siluet hewan atau tanaman.'
            ],
            [
                'waste_types_id' => 6, // Plastic
                'description_mat' => 'Plastik membutuhkan waktu hingga 1000 tahun untuk terurai sepenuhnya. Oleh karena itu, yuk kurangi penggunaan plastik sekali pakai!',
                'recycle_info' => 'Botol plastik bekas yang tidak terpakai dapat dipotong dan dihias menjadi pot bunga gantung yang cantik untuk menghiasi halaman rumah atau balkon.'
            ],
            [
                'waste_types_id' => 6, // Plastic
                'description_mat' => 'Hey Quessers! Sampah plastik yang dibuang ke lautan dapat membahayakan penyu karena ia akan mengira sampah itu adalah makanannya!',
                'recycle_info' => 'Plastik kresek bekas yang biasanya hanya dibuang begitu saja bisa dianyam dengan teknik sederhana menjadi tas belanja ramah lingkungan yang tahan lama.'
            ],

        ];
        DB::table('materials')->insert($materials);
    }
}
