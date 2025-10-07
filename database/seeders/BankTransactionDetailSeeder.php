<?php

namespace Database\Seeders;

use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use App\Models\BankWasteProduct;
use App\Models\RekeningBankSampahUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankTransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BankTransactionDetail::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset semua saldo rekening ke 0 sebelum dihitung ulang
        RekeningBankSampahUser::query()->update(['saldo' => 0]);

        $pemasukanTransactions = BankTransaction::where('transaction_type', 'pemasukan')->with('rekening.bank')->get();
        $wasteProducts = BankWasteProduct::all();

        if ($pemasukanTransactions->isEmpty() || $wasteProducts->isEmpty()) {
            $this->command->info('Tidak ada transaksi pemasukan atau produk sampah, seeder detail dilewati.');
            return;
        }

        foreach ($pemasukanTransactions as $transaction) {
            $availableProducts = $wasteProducts->where('bank_id', $transaction->rekening->bank_id);
            if ($availableProducts->isEmpty()) continue;

            $totalTransactionAmount = 0;

            for ($i = 0; $i < rand(1, 3); $i++) {
                $randomProduct = $availableProducts->random();
                $weight = rand(1, 10);
                $subtotal = $weight * $randomProduct->price_per_kg;

                BankTransactionDetail::create([
                    // ==========================================================
                    // PERBAIKAN DI SINI:
                    // Sesuaikan nama kolom foreign key dengan yang ada di migrasi Anda
                    // ==========================================================
                    'transaction_id' => $transaction->id,

                    'bank_waste_product_id' => $randomProduct->id,
                    'weight_kg' => $weight,
                    'price_per_kg' => $randomProduct->price_per_kg,
                    'subtotal' => $subtotal,
                ]);
                $totalTransactionAmount += $subtotal;
            }

            // Update total di transaksi induk agar konsisten
            $transaction->update(['transaction_amount' => $totalTransactionAmount]);
        }

        // Hitung ulang semua saldo berdasarkan transaksi yang sudah final
        $allTransactions = BankTransaction::all();
        foreach ($allTransactions as $trx) {
            $rekening = $trx->rekening;
            if ($rekening) {
                $rekening->increment('saldo', $trx->transaction_amount);
            }
        }
    }
}
