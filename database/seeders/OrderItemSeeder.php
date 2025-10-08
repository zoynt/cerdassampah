<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        OrderItem::truncate(); 


        $orderIds = Order::pluck('id');
        $productIds = Product::pluck('id');

        if ($orderIds->isEmpty() || $productIds->isEmpty()) {
            $this->command->info('Tidak ada order atau produk untuk membuat order items. Silakan jalankan seeder lain terlebih dahulu.');
            return;
        }

        $items = [
            ['order_id' => $orderIds->random(), 'product_id' => $productIds->random(), 'quantity' => 2, 'price' => 2000],
            ['order_id' => $orderIds->random(), 'product_id' => $productIds->random(), 'quantity' => 1, 'price' => 1500],
            ['order_id' => $orderIds->random(), 'product_id' => $productIds->random(), 'quantity' => 5, 'price' => 2500],
            ['order_id' => $orderIds->random(), 'product_id' => $productIds->random(), 'quantity' => 3, 'price' => 1200],
            ['order_id' => $orderIds->random(), 'product_id' => $productIds->random(), 'quantity' => 10, 'price' => 1000],
        ];

        foreach ($items as $item) {
            OrderItem::create($item);
        }
    }
}