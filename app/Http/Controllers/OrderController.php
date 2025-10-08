<?php

namespace App\Http\Controllers;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\StoreReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }
    public function purchaseHistory()
    {
        // 1. Dapatkan ID pengguna yang sedang login.
        $userId = Auth::id();

        // 2. Ambil semua order_items di mana order-nya dimiliki oleh pengguna ini.
        $purchaseItems = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('buyer_id', $userId);
        })
        ->with([
            // 3. Ambil relasi yang dibutuhkan untuk menampilkan data
            'order', // Untuk status
            'product.store.user' // Untuk nama produk, nama toko, dan nama penjual
        ])
        ->latest() // Urutkan dari yang terbaru
        ->get();

        // 4. Format data agar sesuai dengan yang diharapkan oleh AlpineJS di frontend.
        $transactions = $purchaseItems->map(function ($item) {
            $statusText = 'Pending';
            if ($item->order->status == 'completed') {
                $statusText = 'Selesai';
            } elseif ($item->order->status == 'processing') { 
                $statusText = 'Diproses';
            } elseif ($item->order->status == 'canceled') {
                $statusText = 'Dibatalkan';
            } // Tambahkan kondisi lain jika ada

            return [
                'id' => $item->id,
                'produk' => optional($item->product)->name ?? 'Produk Dihapus',
                'penjual' => optional($item->product->store->user)->name ?? 'Penjual Dihapus',
                'toko' => optional($item->product->store)->name ?? 'Toko Dihapus',
                'hargaSatuan' => (int)$item->price,
                'jumlah' => (int)$item->quantity,
                'total' => (int)($item->price * $item->quantity),
                'status' => $statusText,
                'status_raw' => $item->order->status,
                'detailUrl' => route('marketplace.purchase.detail', ['order' => $item->order_id])
            ];
        });

        // 5. Kirim data yang sudah diformat ke view.
        return view('pages.marketplace.history', [
            'transactions' => $transactions
        ]);
    }
    public function showPurchaseDetail(Order $order)
    {
        // Otorisasi: Pastikan user yang login adalah pembeli dari order ini
        if ($order->buyer_id !== Auth::id() && $order->seller_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        if ($order->status === 'pending') {
        $this->_checkAndUpdateStatus($order);

        // Penting: Muat ulang data order dari database setelah di-update
        // agar view menampilkan status yang terbaru.
        $order->refresh();
        }
        // Ambil relasi yang dibutuhkan
        $order->load(['orderItems.product.store.user', 'buyer', 'seller']);
        $hasReviewed = StoreReview::where('order_id', $order->id)
                              ->where('user_id', Auth::id())
                              ->exists();


        return view('pages.marketplace.purchase-detail', [
        'order' => $order,
        'hasReviewed' => $hasReviewed 
        ]);
    }
    public function placeOrder(Request $request, Product $product)
    {
        // Validasi request
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string',
            'delivery_latitude' => 'required|numeric',
            'delivery_longitude' => 'required|numeric',
        ]);

        $buyer = Auth::user();
        $seller = $product->store->user;
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity > $product->stock) {
            // Jika request datang dari AJAX, kembalikan response JSON
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Jumlah pembelian melebihi stok yang tersedia.'], 422);
            }
            return back()->with('error', 'Jumlah pembelian melebihi stok yang tersedia.');
        }

        $totalAmount = $product->price * $quantity;

        // 1. Buat Order di database dengan status 'pending'
        $order = Order::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $totalAmount,
            'status' => 'pending', // Status internal aplikasi
            'payment_status' => 'pending', // Status dari Midtrans
            'delivery_address' => $request->delivery_address,
            'delivery_latitude' => $request->delivery_latitude,
            'delivery_longitude' => $request->delivery_longitude,
        ]);

        // 2. Buat Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        // 3. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 4. Buat Parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number, // Gunakan order_number yang unik
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $buyer->name,
                'email' => $buyer->email,
                // 'phone' => $buyer->phone, // Jika ada nomor telepon
            ],
            'item_details' => [[
                'id' => $product->id,
                'price' => $product->price,
                'quantity' => $quantity,
                'name' => $product->name,
            ]],
        ];

        try {
            // 5. Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // 6. Simpan Snap Token ke database
            $order->snap_token = $snapToken;
            $order->save();

            // 7. Kembalikan token ke frontend
            return response()->json([
                'snap_token' => $snapToken,
                'redirect_url' => route('marketplace.purchase.detail', $order)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function cancelOrder(Order $order)
    {
        // 1. Otorisasi: Pastikan hanya pembeli yang bisa membatalkan pesanannya sendiri.
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'AKSES DITOLAK.');
        }

        // 2. Validasi: Pastikan hanya order dengan status 'pending' yang bisa dibatalkan oleh pengguna.
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan yang sedang diproses atau sudah selesai tidak dapat dibatalkan.');
        }

        // 3. Update status pesanan menjadi 'canceled'
        $order->status = 'canceled';
        $order->save();

        // 4. Kembalikan ke halaman riwayat transaksi dengan pesan sukses.
        return redirect()->route('marketplace.history')->with('success', 'Pesanan berhasil dibatalkan.');
    }
    private function _checkAndUpdateStatus(Order $order)
    {
        // Set kredensial Midtrans Anda
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production');
        $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        // Buat instance Guzzle Client
        $client = new Client([
            'base_uri' => $baseUrl,
        ]);

        try {
            // Lakukan request GET ke API Midtrans
            $response = $client->request('GET', "/v2/{$order->order_number}/status", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'auth' => [$serverKey, ''] // Otentikasi menggunakan Server Key
            ]);

            $body = json_decode($response->getBody()->getContents());

            // Periksa status transaksi dari respons API
            $newStatus = $body->transaction_status;

            // Logika update status (sama seperti di callback)
            if ($newStatus == 'capture' || $newStatus == 'settlement') {
                $order->payment_status = 'success';
                $order->status = 'processing';
            } elseif ($newStatus == 'pending') {
                $order->payment_status = 'pending';
            } elseif ($newStatus == 'deny' || $newStatus == 'cancel') {
                $order->payment_status = 'denied';
                $order->status = 'canceled';
            } elseif ($newStatus == 'expire') {
                $order->payment_status = 'expired';
                $order->status = 'canceled';
            }
            
            // Simpan metode pembayaran jika belum ada
            if (isset($body->payment_type) && is_null($order->payment_method)) {
                $order->payment_method = $body->payment_type;
            }

            // Simpan perubahan ke database
            $order->save();

        } catch (RequestException $e) {
            // Tangani jika order tidak ditemukan di Midtrans atau terjadi error lain
            Log::error('Gagal mengecek status Midtrans: ' . $e->getMessage());
        }
    }
    
    public function markAsCompleted(Order $order)
    {
        // Otorisasi: Pastikan user yang login adalah PENJUAL dari order ini
        if ($order->seller_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK: Anda bukan penjual dari pesanan ini.');
        }

        // Ubah status dan simpan
        $order->status = 'completed';
        $order->save();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi Selesai.');
    }
    public function showInvoice(Order $order)
    {
        // Otorisasi: Pastikan user yang login adalah pembeli atau penjual dari order ini
        if ($order->buyer_id !== Auth::id() && $order->seller_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        
        // Eager load semua relasi yang dibutuhkan oleh view invoice
        $order->load(['buyer', 'seller.store', 'orderItems.product']);

        // Kirim data ke view
        return view('pages.marketplace.invoice', [
            'order' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
