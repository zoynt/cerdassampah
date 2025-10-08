<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\StoreReview;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesHistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultiSheetSalesExport;


class ProductController extends Controller
{
        public function index(Request $request) 
    {
        $productsQuery = Product::with(['category', 'store.reviews', 'images'])
            ->withSum(['orderItems as sold_count' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('status', 'completed');
                });
            }], 'quantity')
            ->latest();

        $allProducts = $productsQuery->get();

        $productsFormatted = $allProducts->map(function ($product) {
            $firstImage = $product->images->first();
            $averageRating = $product->store ? $product->store->reviews->avg('rating') : 0;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->product_category_id,
                'category' => optional($product->category)->slug, 
                'price' => (int)$product->price,
                'stock' => $product->stock,
                'satuan_berat' => $product->selling_unit ?? 'Tidak Ada',
                'store' => optional($product->store)->name ?? 'Toko Tidak Dikenal',
                'rating' => number_format($averageRating ?? 0, 1),
                'sold' => (int)($product->sold_count ?? 0),
                'image' => $firstImage ? asset('storage/' . $firstImage->image_path) : asset('img/placeholder.png'),
            ];
        });

        $categories = ProductCategory::all()->map(function ($category) {
            return [ 'id' => $category->id, 'name' => $category->name, 'icon' => $category->icon ];
        });
        
        return view('pages.marketplace.products_all', [
            'products' => $productsFormatted,
            'categories' => $categories
        ]);
    }
    public function show(Product $product)
    {
 
        $product->load(['images', 'store.reviews.user', 'orderItems.order']);

        $reviewsFormatted = $product->store->reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'name' => optional($review->user)->name ?? 'Pengguna', 
                'rating' => $review->rating,
                'comment' => $review->review,
                'date' => $review->created_at->diffForHumans(),
            ];
        });

        return view('pages.marketplace.detail', [
            'product' => $product,
            'reviewsFormatted' => $reviewsFormatted,
        ]);
    }
    /**
     * Menampilkan daftar produk milik user yang sedang login.
     */
    public function storeProducts(Request $request)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('store.profile.create')
                             ->with('info', 'Anda harus membuat profil toko terlebih dahulu untuk mengelola produk.');
        }

        $productsQuery = $store->products()->with(['category', 'images'])->latest();
        $products = $productsQuery->paginate(10);

        return view('pages.marketplace.products_list', [
            'products' => $products,
            'store' => $store,
        ]);
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {

        if (!Auth::user()->store) {
             return redirect()->route('store.profile.create')
                             ->with('info', 'Anda harus membuat profil toko terlebih dahulu untuk menambah produk.');
        }

        $kategoriList = ProductCategory::pluck('name')->all();
        $statusList = ['Tersedia', 'Habis'];

        return view('pages.marketplace.create', [
            'produk' => new Product(), 
            'kategoriList' => $kategoriList,
            'statusList' => $statusList,
        ]);
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|exists:product_categories,name',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'bobot' => 'required|numeric|min:0',
            'satuan_berat' => 'required|string',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|array|min:1', 
            'gambar.*' => 'image|max:10248', 
        ]);

        $store = Auth::user()->store;
        if (!$store) {
            return back()->with('error', 'Anda harus memiliki toko untuk menambahkan produk.');
        }

        $category = ProductCategory::where('name', $validatedData['kategori'])->firstOrFail();

        $dataToStore = [
            'store_id' => $store->id,
            'product_category_id' => $category->id,
            'name' => $validatedData['nama'],
            'price' => $validatedData['harga'],
            'stock' => $validatedData['stok'],
            'weight_per_item' => $validatedData['bobot'],
            'selling_unit' => $validatedData['satuan_berat'],
            'status' => ($validatedData['status'] == 'Tersedia') ? 'available' : 'sold_out',
            'description' => $validatedData['deskripsi'],
        ];

        // MENGGUNAKAN METODE PENYIMPANAN MANUAL YANG SUDAH PASTI BERHASIL
        $product = new Product();
        $product->fill($dataToStore);
        $product->save();

        if ($request->hasFile('gambar')) {   
            foreach ($request->file('gambar') as $key => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => ($key == 0),
                ]);
            }
        }

        return redirect()->route('marketplace.products.list')->with('success', 'Produk berhasil ditambahkan!');
    }
    public function showCheckout(Request $request)
    {
        // Validasi untuk memastikan parameter product dan quantity ada
        $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'image_id' => 'nullable|exists:product_images,id' // Validasi image_id
        ]);

        // Ambil data dari URL
        $productId = $request->query('product');
        $quantity = $request->query('quantity');
        $selectedImageId = $request->query('image_id');

        // Cari produk dari database beserta relasi tokonya
        $product = Product::with(['store', 'images'])->findOrFail($productId);
        
        // Tentukan gambar mana yang akan ditampilkan di checkout
        $checkoutImage = null;
        if ($selectedImageId) {
            // Cari gambar yang dipilih berdasarkan ID
            $checkoutImage = $product->images->find($selectedImageId);
        }
        
        // Jika tidak ada gambar yang dipilih atau ID-nya tidak valid, gunakan gambar pertama sebagai fallback
        if (!$checkoutImage) {
            $checkoutImage = $product->images->first();
        }

        // Kirim data produk, kuantitas, dan GAMBAR YANG DIPILIH ke view
        return view('pages.marketplace.checkout', [
            'product' => $product,
            'quantity' => $quantity,
            'checkoutImage' => $checkoutImage
        ]);
    }
    /**
     * Menampilkan riwayat penjualan untuk toko milik user yang sedang login.
     */
    public function riwayatPenjualan(Request $request)
    {
        // 1. Dapatkan toko milik user (TIDAK BERUBAH)
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('store.profile.create')->with('info', 'Anda harus memiliki toko untuk melihat riwayat penjualan.');
        }

        // 2. [UBAH] Ambil SEMUA pesanan (tanpa filter status di sini)
    $query = \App\Models\Order::where('seller_id', $store->user_id)
                ->with(['buyer', 'orderItems.product.category'])
                ->latest();

    // 3. [UBAH] Format semua data untuk dikirim ke Alpine.js
    $penjualansForJs = $query->get()->map(function($order) {
        return [
            'order_id' => $order->id,
            'pembeli' => optional($order->buyer)->name ?? 'Pembeli Dihapus',
            'produk_list' => $order->orderItems->pluck('product.name')->join(', '),
            'kategori' => optional($order->orderItems->first()->product->category)->name ?? '-', // Ambil kategori pertama
            'jumlah_item' => $order->orderItems->sum('quantity'),
            'total' => (int)$order->total_amount,
            'status' => $order->status,
            'detailUrl' => route('marketplace.purchase.detail', ['order' => $order->id])
        ];
    });

        // --- Kalkulasi untuk kartu statistik & grafik (TIDAK BERUBAH BANYAK) ---
        // Logikanya tetap sama, hanya memastikan query-nya benar
        $completedQuery = \App\Models\OrderItem::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->whereHas('order', function ($q) {
            $q->where('status', 'completed');
        });
        $totalProduk = (clone $completedQuery)->sum('quantity');
        $totalPenjualan = (clone $completedQuery)->sum(DB::raw('price * quantity'));
        $salesLast7Days = (clone $completedQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(price * quantity) as total_sales'))
            ->groupBy('date')->orderBy('date', 'ASC')->get()->keyBy('date');

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->translatedFormat('d M'); 
            $chartData[] = $salesLast7Days[$date]->total_sales ?? 0;
        }
        // --- Akhir Kalkulasi Statistik ---

        return view('pages.marketplace.riwayat', [
        'penjualans' => $penjualansForJs,
        'kategoriList' => \App\Models\ProductCategory::pluck('name')->all(),
        'totalProduk' => $totalProduk,
        'totalPenjualan' => 'Rp ' . number_format($totalPenjualan, 0, ',', '.'),
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
        ]);
    }


    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        
        
        if ($product->store_id !== Auth::user()->store->id) {
            abort(403, 'AKSES DITOLAK');
        }

        $kategoriList = ProductCategory::pluck('name')->all();
        $statusList = ['Tersedia', 'Habis'];
        
        return view('pages.marketplace.edit', [
            'produk' => $product,
            'kategoriList' => $kategoriList,
            'statusList' => $statusList,
        ]);
    }

    /**
     * Memperbarui produk di database.
     */
    public function update(Request $request, Product $product)
    {
        if ($product->store_id !== Auth::user()->store->id) {
            abort(403);
        }

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|exists:product_categories,name',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'bobot' => 'required|numeric|min:0',
            'satuan_berat' => 'required|string',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|array', 
            'gambar.*' => 'image|max:10248', 
            'images_to_delete' => 'nullable|array',
        ]);

        $category = ProductCategory::where('name', $validatedData['kategori'])->firstOrFail();

        $dataToUpdate = [
            'product_category_id' => $category->id,
            'name' => $validatedData['nama'],
            'price' => $validatedData['harga'],
            'stock' => $validatedData['stok'],
            'weight_per_item' => $validatedData['bobot'],
            'selling_unit' => $validatedData['satuan_berat'],
            'status' => ($validatedData['status'] == 'Tersedia') ? 'available' : 'sold_out',
            'description' => $validatedData['deskripsi'],
        ];

        $product->update($dataToUpdate);

            if ($request->has('images_to_delete') && !$request->hasFile('gambar')) {
            $pathsToDelete = $request->input('images_to_delete');
            $imagesToDelete = ProductImage::where('product_id', $product->id)
                                            ->whereIn('image_path', $pathsToDelete)
                                            ->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // 2. Proses gambar baru yang di-upload (logika "Ganti Semua")
        if ($request->hasFile('gambar')) {
            // Hapus semua gambar lama
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }
            // Simpan semua gambar baru
            foreach ($request->file('gambar') as $key => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => ($key == 0),
                ]);
            }
        }

        return redirect()->route('marketplace.products.list')->with('success', 'Produk berhasil diperbarui!');
    }
    public function exportSalesHistory(Request $request)
    {
        $fileName = 'riwayat-penjualan-semua-status-' . date('Y-m-d') . '.xlsx';

        // Panggil class export multi-sheet yang baru
        return Excel::download(new MultiSheetSalesExport(), $fileName);
    }
    public function showRatingForm(Order $order)
    {
        // Pastikan hanya pembeli yang bisa mengakses
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Akses Ditolak');
        }
        $review = StoreReview::where('order_id', $order->id)
                            ->where('user_id', Auth::id())
                            ->first();

        // Kirim data order dan review (bisa jadi null jika belum ada)
        return view('pages.marketplace.rating', [
            'order' => $order,
            'review' => $review
        ]);
    }

    /**
     * Menyimpan data rating dan ulasan ke database.
     */
    public function storeRating(Request $request, Order $order)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // 2. Otorisasi (pastikan lagi user adalah pembeli)
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Akses Ditolak');
        }

        // 3. Dapatkan ID toko dari item pertama dalam order
        // Asumsi: 1 order hanya berasal dari 1 toko
        $store_id = $order->orderItems->first()->product->store_id;

        // 4. Simpan ulasan
        StoreReview::updateOrCreate(
        [
            'order_id' => $order->id,
            'user_id'  => Auth::id()
        ],
        [
            'store_id' => $store_id,
            'rating'   => $request->rating,
            'review'   => $request->review
        ]
        );
        return redirect()->route('marketplace.purchase.detail', $order)
                     ->with('success', 'Ulasan Anda berhasil disimpan!');
    }


    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        // Otorisasi
        if ($product->store_id !== Auth::user()->store->id) {
            abort(403);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $product->delete();

        return redirect()->route('marketplace.products.list')->with('success', 'Produk berhasil dihapus!');
    }
}