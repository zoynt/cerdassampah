<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use Carbon\Carbon;

class BankTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua rekening yang ada
        $rekenings = RekeningBankSampahUser::all();

        if ($rekenings->isEmpty()) {
            $this->command->info('Tidak ada data rekening, seeder BankTransaction dilewati.');
            return;
        }

        // Buat beberapa transaksi untuk setiap rekening
        foreach ($rekenings as $rekening) {
            for ($i = 0; $i < 5; $i++) {
                // 1 transaksi penarikan
                BankTransaction::create([
                    'rekening_id' => $rekening->id,
                    'transaction_code' => 'WDR' . time() . $i,
                    'transaction_amount' => -1 * rand(10000, 25000), // Angka negatif untuk penarikan
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);

                // 2 transaksi pemasukan
                BankTransaction::create([
                    'rekening_id' => $rekening->id,
                    'transaction_code' => 'DEP' . time() . $i,
                    'transaction_amount' => rand(5000, 15000), // Angka positif untuk pemasukan
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
