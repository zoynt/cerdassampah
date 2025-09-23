<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RekeningBankSampahUser;
use App\Models\User;
use App\Models\Bank;

class RekeningBankSampahUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel users dan banks sudah terisi.
        // Seeder ini mengasumsikan ada user dengan ID 1 & 2, dan bank dengan ID 1, 2, & 3.
        $user1 = User::find(1);
        $user2 = User::find(2);
        $bank1 = Bank::find(1);
        $bank2 = Bank::find(2);
        $bank3 = Bank::find(3);

        // Lanjutkan hanya jika data user dan bank ditemukan
        if ($user1 && $user2 && $bank1 && $bank2 && $bank3) {
            $rekenings = [
                // User 1 punya rekening di 3 bank berbeda
                [
                    'user_id' => $user1->id,
                    'bank_id' => $bank1->id,
                    'rekening_number' => 'REK' . $user1->id . $bank1->id . mt_rand(100, 999),
                    'saldo' => mt_rand(50000, 200000),
                ],
                [
                    'user_id' => $user1->id,
                    'bank_id' => $bank2->id,
                    'rekening_number' => 'REK' . $user1->id . $bank2->id . mt_rand(100, 999),
                    'saldo' => mt_rand(25000, 100000),
                ],
                [
                    'user_id' => $user1->id,
                    'bank_id' => $bank3->id,
                    'rekening_number' => 'REK' . $user1->id . $bank3->id . mt_rand(100, 999),
                    'saldo' => mt_rand(75000, 150000),
                ],
                // User 2 punya rekening di 2 bank
                [
                    'user_id' => $user2->id,
                    'bank_id' => $bank1->id,
                    'rekening_number' => 'REK' . $user2->id . $bank1->id . mt_rand(100, 999),
                    'saldo' => mt_rand(10000, 50000),
                ],
                [
                    'user_id' => $user2->id,
                    'bank_id' => $bank3->id,
                    'rekening_number' => 'REK' . $user2->id . $bank3->id . mt_rand(100, 999),
                    'saldo' => mt_rand(30000, 60000),
                ],
            ];

            foreach ($rekenings as $rekening) {
                RekeningBankSampahUser::create($rekening);
            }
        } else {
            // Beri tahu di console jika data user/bank tidak ditemukan
            $this->command->info('User atau Bank tidak ditemukan, seeder RekeningBankSampahUser dilewati.');
        }
    }
}
