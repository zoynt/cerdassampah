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
            // === ORGANIK (ID: 1) ===
            [
                'waste_types_id' => 1,
                'description_mat' => 'Sisa makanan seperti nasi, sayuran, dan buah-buahan termasuk sampah organik yang mudah membusuk dan menghasilkan gas metana jika dibuang ke TPA.',
                'suggest' => 'Olah sampah organik menjadi kompos di rumah menggunakan komposter atau lubang biopori.',
                'recycle_info' => 'Kompos yang dihasilkan adalah pupuk alami yang sangat baik untuk menyuburkan tanaman tanpa bahan kimia.'
            ],
            [
                'waste_types_id' => 1,
                'description_mat' => 'Sampah kebun seperti daun kering, ranting, dan sisa potongan rumput adalah bahan utama untuk membuat kompos berkualitas tinggi.',
                'suggest' => 'Jangan membakar sampah kebun, karena asapnya menimbulkan polusi. Manfaatkan sebagai bahan kompos.',
                'recycle_info' => 'Dengan mengompos, Anda mengurangi volume sampah yang dikirim ke TPA sekaligus mendapatkan pupuk gratis.'
            ],

            // === ANORGANIK (ID: 2) ===
            [
                'waste_types_id' => 2,
                'description_mat' => 'Tahukah kamu? Sebuah kaleng aluminium bisa didaur ulang dan kembali ke rak toko sebagai kaleng baru hanya dalam 60 hari!',
                'suggest' => 'Pastikan untuk membersihkan kemasan plastik, kaleng, atau botol kaca sebelum memberikannya ke bank sampah.',
                'recycle_info' => 'Daur ulang sampah anorganik menghemat sumber daya alam, energi, dan mengurangi polusi secara signifikan.'
            ],
            [
                'waste_types_id' => 2,
                'description_mat' => 'Dibutuhkan waktu hingga 1.000 tahun agar satu botol plastik dapat terurai di alam. Sampah plastik di lautan sering dikira makanan oleh hewan laut.',
                'suggest' => 'Pisahkan sampah anorganik (plastik, kertas, logam, kaca) dari sampah lainnya untuk memudahkan proses daur ulang.',
                'recycle_info' => 'Botol plastik bekas dapat diolah menjadi serat kain (polyester) untuk membuat pakaian, tas, atau sepatu.'
            ],
             [
                'waste_types_id' => 2,
                'description_mat' => 'Mendaur ulang satu ton kertas dapat menyelamatkan sekitar 17 pohon besar dan menghemat ribuan liter air.',
                'suggest' => 'Gunakan kedua sisi kertas dan kumpulkan kertas bekas untuk didaur ulang. Hindari kertas yang terkontaminasi minyak.',
                'recycle_info' => 'Kertas yang didaur ulang dapat diubah menjadi produk baru seperti kardus, koran, atau kertas tisu.'
            ],

            // === RESIDU (ID: 3) ===
            [
                'waste_types_id' => 3,
                'description_mat' => 'Residu adalah sampah sisa yang sulit didaur ulang, contohnya styrofoam, popok sekali pakai, pembalut, dan puntung rokok.',
                'suggest' => 'Cara terbaik mengelola residu adalah dengan mengurangi penggunaannya. Pilih produk dengan kemasan minimal atau yang dapat digunakan kembali.',
                'recycle_info' => 'Di beberapa negara maju, sampah residu diolah menjadi sumber energi listrik melalui teknologi waste-to-energy.'
            ],
            [
                'waste_types_id' => 3,
                'description_mat' => 'Baterai bekas, lampu neon, dan sampah elektronik mengandung logam berat berbahaya (seperti merkuri dan timbal) yang dapat meracuni tanah dan air.',
                'suggest' => 'Jangan buang sampah B3 (Bahan Berbahaya dan Beracun) ke tempat sampah biasa. Kumpulkan dan serahkan ke titik pengumpulan limbah B3.',
                'recycle_info' => 'Komponen berharga dari limbah elektronik dapat diekstraksi secara aman untuk digunakan kembali, mencegah kerusakan lingkungan.'
            ],
        ];
        DB::table('materials')->insert($materials);
    }
}