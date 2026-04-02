<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;
use App\Models\RekeningBankSampahUser;
use App\Models\BankTransaction;
use App\Models\BankTransactionDetail;
use App\Models\BankWasteProduct; // Pastikan use model ini
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use Carbon\Carbon; // Untuk manipulasi tanggal
use Illuminate\Support\Str; // Untuk UUID

class TransaksiBankMajuJayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankIdMajuJaya = 6;
        $jumlahTransaksiPerNasabah = rand(3, 7);
        $jumlahDetailPerTransaksi = rand(1, 4);

        // === 1. Ambil Data ===
        $bank = Bank::find($bankIdMajuJaya);
        if (!$bank) {
            $this->command->error("Bank dengan ID {$bankIdMajuJaya} tidak ditemukan.");
            return;
        }

        $nasabahs = RekeningBankSampahUser::where('bank_id', $bankIdMajuJaya)->get();
        if ($nasabahs->isEmpty()) {
            $this->command->warn("Tidak ada nasabah ditemukan untuk Bank ID {$bankIdMajuJaya}. Seeder transaksi dilewati.");
            return;
        }

        $itemSampahTersedia = BankWasteProduct::where('bank_id', $bankIdMajuJaya)
                                             ->where('status', 'Aktif')
                                             ->get();
        if ($itemSampahTersedia->isEmpty()) {
            $this->command->error("Tidak ada item sampah (BankWasteProduct) yang AKTIF ditemukan untuk Bank ID {$bankIdMajuJaya}. Tambahkan item terlebih dahulu.");
            return;
        }
        $itemSampahLookup = $itemSampahTersedia->pluck('price_per_kg', 'id');

        $this->command->info("Membuat data transaksi untuk nasabah Bank: {$bank->bank_name}...");

        // === 2. Looping Nasabah ===
        foreach ($nasabahs as $rekeningNasabah) {
            $this->command->line("  -> Membuat transaksi untuk Nasabah ID: {$rekeningNasabah->user_id} (Rekening: {$rekeningNasabah->rekening_number})");
            $totalSaldoTambahan = 0;

            // === 3. Buat Transaksi per Nasabah ===
            for ($i = 0; $i < $jumlahTransaksiPerNasabah; $i++) {
                DB::transaction(function () use ($rekeningNasabah, $itemSampahLookup, $jumlahDetailPerTransaksi, &$totalSaldoTambahan) {
                    $totalAmountTransaksi = 0;
                    $tanggalTransaksi = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));

                    // === 4. Buat Transaksi Induk ===
                    $transaksi = BankTransaction::create([
                        'rekening_id' => $rekeningNasabah->id,
                        'uuid' => (string) Str::uuid(),
                        'transaction_type' => 'pemasukan', // Menggunakan ENUM yang benar
                        'description' => 'Setoran sampah otomatis via Seeder',
                        'transaction_amount' => 0,
                        'created_at' => $tanggalTransaksi,
                        'updated_at' => $tanggalTransaksi,
                    ]);

                    // === 5. Buat Detail Transaksi ===
                    for ($j = 0; $j < $jumlahDetailPerTransaksi; $j++) {
                        if ($itemSampahLookup->isEmpty()) {
                            $this->command->error("Tidak ada item sampah valid di lookup.");
                            throw new \Exception("Item sampah lookup kosong.");
                        }
                        $randomItemId = $itemSampahLookup->keys()->random();
                        $hargaPerKg = $itemSampahLookup[$randomItemId];
                        $beratKg = rand(10, 50) / 10.0;
                        $subtotal = $hargaPerKg * $beratKg;

                        BankTransactionDetail::create([
                            'transaction_id' => $transaksi->id,
                            // ======================================
                            // [PERBAIKAN] Menggunakan nama kolom yang benar
                            // ======================================
                            'bank_waste_product_id' => $randomItemId, // Ganti dari 'waste_product_id'
                            // ======================================
                            'weight_kg' => $beratKg,
                            'price_per_kg' => $hargaPerKg,
                            'subtotal' => $subtotal,
                            'created_at' => $tanggalTransaksi,
                            'updated_at' => $tanggalTransaksi,
                        ]);

                        $totalAmountTransaksi += $subtotal;
                    }

                    // === 6. Update Total Amount Transaksi Induk ===
                    $transaksi->transaction_amount = $totalAmountTransaksi;
                    $transaksi->save();

                    $totalSaldoTambahan += $totalAmountTransaksi;
                }); // Akhir DB::transaction
            } // Akhir loop transaksi

            // === 7. Update Saldo Nasabah ===
            $rekeningNasabah->saldo += $totalSaldoTambahan;
            $rekeningNasabah->save();
            $this->command->line("     => Saldo Nasabah ID {$rekeningNasabah->user_id} diupdate.");

        } // Akhir loop nasabah

        $this->command->info("Seeder TransaksiBankMajuJayaSeeder selesai.");
    }
}