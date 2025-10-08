<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrderItem::truncate();
        Order::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Membuat data pesanan dan item pesanan...');

        $myUserId = 12;
        $myUser = User::find($myUserId);
        $sellers = User::whereHas('store')->where('id', '!=', $myUserId)->get();
        $otherBuyers = User::where('id', '!=', $myUserId)->limit(5)->get();

        if ($sellers->isEmpty() || $otherBuyers->isEmpty()) {
            $this->command->error('Tidak cukup data User atau Penjual (User dengan Store).');
            return;
        }

        // --- SKENARIO UNTUK MENGISI GRAFIK ANDA ---
        if ($myUser && $myUser->store) {
            $this->command->info('Membuat 5 pesanan untuk toko Anda (User ID 12) dalam 7 hari terakhir...');
            for ($i = 0; $i < 5; $i++) {
                $buyer = $otherBuyers->random();
                // TANGGAL DIBUAT ACAK DALAM 7 HARI TERAKHIR (0-6 HARI LALU)
                $date = Carbon::now()->subDays(rand(0, 6)); 
                $this->createOrderForBuyer($buyer, $myUser, $date);
            }
        } else {
            $this->command->warn('User ID 12 tidak memiliki toko, skenario "Anda sebagai Penjual" dilewati.');
        }
        // --- AKHIR SKENARIO GRAFIK ---

        $this->command->info('Membuat 3 pesanan di mana Anda (User ID 12) adalah pembeli...');
        for ($i = 0; $i < 3; $i++) {
            $seller = $sellers->random();
            $date = Carbon::now()->subDays(rand(8, 30));
            $this->createOrderForBuyer($myUser, $seller, $date);
        }

        $this->command->info('Membuat 5 pesanan acak lainnya...');
        for ($i = 0; $i < 5; $i++) {
            $buyer = User::inRandomOrder()->first();
            $seller = $sellers->where('id', '!=', $buyer->id)->random();
            $date = Carbon::now()->subDays(rand(0, 30));
            $this->createOrderForBuyer($buyer, $seller, $date);
        }
    }

    private function createOrderForBuyer(User $buyer, User $seller, Carbon $creationDate)
    {
        if (!$seller->store || $seller->store->products->isEmpty()) {
            return;
        }
        $productsToBuy = $seller->store->products()->inRandomOrder()->take(rand(1, 2))->get();
        if ($productsToBuy->isEmpty()) return;

        $order = Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => 0,
            'status' => 'completed',
            'created_at' => $creationDate,
            'updated_at' => $creationDate,
        ]);

        $totalAmount = 0;
        foreach ($productsToBuy as $product) {
            $quantity = rand(1, 5);
            $subtotal = $product->price * $quantity;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'created_at' => $creationDate,
                'updated_at' => $creationDate,
            ]);
            $totalAmount += $subtotal;
        }
        $order->total_amount = $totalAmount;
        $order->save();
    }
}