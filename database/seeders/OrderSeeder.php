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

        // Pastikan ID ini sesuai dengan User yang Anda login saat ini
        $myUserId = 1; 
        $myUser = User::find($myUserId);
        
        // Ambil seller (user yang punya toko) selain diri sendiri
        $sellers = User::whereHas('store')->where('id', '!=', $myUserId)->get();
        // Ambil buyer (user biasa) selain diri sendiri
        $otherBuyers = User::where('id', '!=', $myUserId)->limit(5)->get();

        if ($sellers->isEmpty() || $otherBuyers->isEmpty()) {
            $this->command->error('Tidak cukup data User atau Penjual (User dengan Store). Pastikan StoreSeeder sudah jalan.');
            return;
        }

        // --- 1. SKENARIO TOKO ANDA MENDAPAT ORDER (Grafik Penjualan) ---
        if ($myUser && $myUser->store) {
            $this->command->info('Membuat 5 pesanan masuk untuk toko Anda...');
            for ($i = 0; $i < 5; $i++) {
                $buyer = $otherBuyers->random();
                // Acak tanggal 7 hari terakhir
                $date = Carbon::now()->subDays(rand(0, 6)); 
                $this->createOrderForBuyer($buyer, $myUser, $date);
            }
        } else {
            $this->command->warn('User ID ' . $myUserId . ' tidak memiliki toko, skenario dilewati.');
        }

        // --- 2. SKENARIO ANDA BELANJA (Riwayat Pembelian) ---
        $this->command->info('Membuat 3 pesanan keluar (Anda belanja)...');
        for ($i = 0; $i < 3; $i++) {
            $seller = $sellers->random();
            $date = Carbon::now()->subDays(rand(8, 30));
            $this->createOrderForBuyer($myUser, $seller, $date);
        }

        // --- 3. SKENARIO ORDER ACAK LAINNYA ---
        $this->command->info('Membuat 5 pesanan acak lainnya...');
        for ($i = 0; $i < 5; $i++) {
            $buyer = User::inRandomOrder()->first();
            // Pastikan seller bukan buyer itu sendiri
            $seller = $sellers->where('id', '!=', $buyer->id)->first();
            
            if ($seller) {
                $date = Carbon::now()->subDays(rand(0, 30));
                $this->createOrderForBuyer($buyer, $seller, $date);
            }
        }
    }

    private function createOrderForBuyer(User $buyer, User $seller, Carbon $creationDate)
    {
        // Cek kelengkapan data seller
        if (!$seller->store || $seller->store->products->isEmpty()) {
            return;
        }

        // Ambil 1-2 produk acak dari toko seller
        $productsToBuy = $seller->store->products()->inRandomOrder()->take(rand(1, 2))->get();
        
        if ($productsToBuy->isEmpty()) return;

        // 1. Buat Order Awal (Total 0 dulu)
        $order = Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => 0,
            'admin_fee' => 0,    // Default
            'net_amount' => 0,   // Default
            'status' => 'completed',
            'payment_status' => 'paid', // Asumsikan sudah bayar agar muncul di history
            'delivery_address' => 'Alamat Dummy Seeder', 
            'delivery_latitude' => 0,
            'delivery_longitude' => 0,
            'created_at' => $creationDate,
            'updated_at' => $creationDate,
        ]);

        $totalAmount = 0;

        // 2. Buat Item Pesanan
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

        // 3. HITUNG DAN UPDATE ADMIN FEE (BAGIAN PENTING)
        $adminFeePercentage = $seller->store->admin_fee ?? 0; // Ambil fee dari toko seller
        $adminFeeAmount = $totalAmount * ($adminFeePercentage / 100);
        $netAmount = $totalAmount - $adminFeeAmount;

        // Update Order dengan total yang benar
        $order->update([
            'total_amount' => $totalAmount,
            'admin_fee' => $adminFeeAmount,
            'net_amount' => $netAmount
        ]);
    }
}