<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RekeningBankSampahUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan tabel rekening
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        RekeningBankSampahUser::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Tentukan ID Bank Sampah Maju Jaya
        $bankIdMajuJaya = 6;

        // 3. Cari bank tersebut
        $bankMajuJaya = Bank::find($bankIdMajuJaya);

        // 4. Hentikan jika bank tidak ditemukan
        if (!$bankMajuJaya) {
            $this->command->error("Bank Sampah dengan ID {$bankIdMajuJaya} (Maju Jaya) tidak ditemukan.");
            return;
        }

        $this->command->info("Membuat nasabah untuk Bank: {$bankMajuJaya->bank_name} (ID: {$bankIdMajuJaya})...");

        // 5. Buat 15 User baru sebagai Nasabah KHUSUS untuk Bank Maju Jaya
        User::factory(15)->create([
            // ===== [PERBAIKAN] Hapus baris 'role' ini =====
            // 'role' => 'user', // Ganti 'user' jika role nasabah Anda berbeda
            // =============================================
            'password' => Hash::make('password'), // Set password default
        ])->each(function ($nasabah) use ($bankIdMajuJaya) {
            // 6. Buatkan Rekening Bank Sampah untuk setiap nasabah baru ini
            RekeningBankSampahUser::create([
                'user_id' => $nasabah->id,
                'bank_id' => $bankIdMajuJaya,
                'rekening_number' => 'REK' . $nasabah->id . $bankIdMajuJaya . now()->timestamp . rand(10,99),
                'saldo' => rand(5000, 250000),
                'status' => 'Aktif',
            ]);
            $this->command->line(" > Nasabah '{$nasabah->name}' dibuat dan didaftarkan ke Bank ID {$bankIdMajuJaya}.");
        });

        $this->command->info("Seeder RekeningBankSampahUserSeeder selesai.");
    }
}