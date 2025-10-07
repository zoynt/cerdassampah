<?php

namespace Database\Seeders;

use App\Models\BankTransaction;
use App\Models\BankTransactionDetail; // <-- Tambahkan ini
use App\Models\RekeningBankSampahUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Tambahkan ini
use Illuminate\Support\Str;

class BankTransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BankTransaction::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rekeningList = RekeningBankSampahUser::all();

        if ($rekeningList->isEmpty()) {
            $this->command->info('Tidak ada data rekening, seeder transaksi dilewati.');
            return;
        }

        foreach ($rekeningList as $rekening) {
            // Buat 5 transaksi pemasukan "kerangka" dengan jumlah awal 0
            for ($i = 0; $i < 5; $i++) {
                BankTransaction::create([
                    'rekening_id' => $rekening->id,
                    'transaction_code' => 'DEP-' . Str::upper(Str::random(10)),
                    'description' => 'Setoran Sampah',
                    'transaction_type' => 'pemasukan',
                    'transaction_amount' => 0, // Akan di-update oleh DetailSeeder
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);
            }

            // Buat 2 transaksi penarikan dengan deskripsi bervariasi
            for ($i = 0; $i < 2; $i++) {
                $metodePenarikan = ['Tunai', 'Transfer Bank', 'E-Wallet'][array_rand(['Tunai', 'Transfer Bank', 'E-Wallet'])];
                $amount = rand(10000, 25000);

                BankTransaction::create([
                    'rekening_id' => $rekening->id,
                    'transaction_code' => 'WDR-' . Str::upper(Str::random(10)),
                    'description' => 'Penarikan via ' . $metodePenarikan, // Deskripsi dinamis
                    'transaction_type' => 'penarikan',
                    'transaction_amount' => -$amount, // Penarikan selalu negatif
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
