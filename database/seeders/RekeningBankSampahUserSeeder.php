<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekeningBankSampahUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERBAIKAN 1: Praktik terbaik untuk membersihkan tabel sebelum seeding
        // Nonaktifkan foreign key check untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        RekeningBankSampahUser::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // PERBAIKAN 2: Ambil SEMUA user dan bank secara dinamis
        // Kode tidak akan error meskipun ID user atau bank berubah
        $users = User::all();
        $banks = Bank::all();

        // Lanjutkan hanya jika ada data user dan bank
        if ($users->isEmpty() || $banks->isEmpty()) {
            $this->command->info('Tidak ada data User atau Bank, seeder Rekening dilewati.');
            return; // Hentikan seeder jika tidak ada data induk
        }

        // PERBAIKAN 3: Buat rekening untuk setiap user di setiap bank secara otomatis
        foreach ($users as $user) {
            foreach ($banks as $bank) {
                // Gunakan firstOrCreate untuk menghindari duplikasi jika seeder dijalankan lagi
                RekeningBankSampahUser::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'bank_id' => $bank->id,
                    ],
                    [
                        // Nomor rekening dibuat unik dan lebih sulit ditebak
                        'rekening_number' => 'REK' . $user->id . $bank->id . now()->timestamp,
                        'saldo' => 0, // Saldo awal seharusnya selalu 0
                    ]
                );
            }
        }
    }
}
