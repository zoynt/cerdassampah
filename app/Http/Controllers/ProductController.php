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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultiSheetSalesExport;



class ProductController extends Controller
{
        public function index(Request $request) 
    {
        $today = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
        $productsQuery = Product::where('status', 'available')
            ->whereHas('store', function ($query) use ($today) {
                $query->where('is_active', true)
                    ->whereRaw('LOWER(operational_days) LIKE ?', ['%"' . $today . '"%']);
            })
            ->with(['category', 'store.reviews', 'images'])
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
                'slug' => Str::slug($product->name),
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
                'store_slug' => optional($product->store)->slug, 
                
                'store_lat' => optional($product->store)->latitude,
                'store_lng' => optional($product->store)->longitude,
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
    public function guest(Request $request) 
    {
        $today = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
        $productsQuery = Product::where('status', 'available')
            ->whereHas('store', function ($query) use ($today) {
                $query->where('is_active', true)
                    ->whereRaw('LOWER(operational_days) LIKE ?', ['%"' . $today . '"%']);
            })
            ->with(['category', 'store.reviews', 'images'])
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
                'slug' => Str::slug($product->name),
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
                'store_slug' => optional($product->store)->slug, 
                
                'store_lat' => optional($product->store)->latitude,
                'store_lng' => optional($product->store)->longitude,
            ];
        });

        $categories = ProductCategory::all()->map(function ($category) {
            return [ 'id' => $category->id, 'name' => $category->name, 'icon' => $category->icon ];
        });
        
        return view('pages.store.store', [
            'products' => $productsFormatted,
            'categories' => $categories
        ]);
    }
    public function showguest(Store $store, $product_slug)
    {
        $id = \Illuminate\Support\Str::of($product_slug)->afterLast('-');
        $slugFromUrl = \Illuminate\Support\Str::of($product_slug)->beforeLast('-');
        $product = $store->products()->find($id);
        if (!$product || (string) $slugFromUrl !== \Illuminate\Support\Str::slug($product->name)) {
            abort(404);
        }
        $isStoreOpen = false; 
        if ($product->store && $product->store->is_active) {
            $today = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
            $isOpenToday = is_array($product->store->operational_days) && in_array($today, array_map('strtolower', $product->store->operational_days));

            if ($isOpenToday) {
                $isStoreOpen = true;
            }
        }
    
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

        return view('pages.store.detail', [
            'product' => $product,
            'isStoreOpen' => $isStoreOpen,
            'reviewsFormatted' => $reviewsFormatted,
        ]);
    }
    public function show(Store $store, $product_slug)
    {
        $id = \Illuminate\Support\Str::of($product_slug)->afterLast('-');
        $slugFromUrl = \Illuminate\Support\Str::of($product_slug)->beforeLast('-');
        $product = $store->products()->find($id);
        if (!$product || (string) $slugFromUrl !== \Illuminate\Support\Str::slug($product->name)) {
            abort(404);
        }
            $isStoreOpen = false; 
        if ($product->store && $product->store->is_active) {
            $today = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
            $isOpenToday = is_array($product->store->operational_days) && in_array($today, array_map('strtolower', $product->store->operational_days));

            if ($isOpenToday) {
                $isStoreOpen = true;
            }
        }
    
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
            'isStoreOpen' => $isStoreOpen,
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
        $statusList = ['Tersedia', 'Diarsipkan'];

        return view('pages.marketplace.create', [
            'product' => new Product(), 
            'kategoriList' => $kategoriList,
            'statusList' => $statusList,
        ]);
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $request->merge(['nama' => Str::title($request->nama)]);
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

        $status = match($validatedData['status']) {
            'Diarsipkan' => 'draft',
            default => 'available',
        };
        if ((float)$validatedData['stok'] <= 0) {
            $status = 'sold';
        }

        $dataToStore = [
            'store_id' => $store->id,
            'product_category_id' => $category->id,
            'name' => $validatedData['nama'],
            'price' => $validatedData['harga'],
            'stock' => $validatedData['stok'],
            'weight_per_item' => $validatedData['bobot'],
            'selling_unit' => $validatedData['satuan_berat'],
            'status' => $status, 
            'description' => $validatedData['deskripsi'],
        ];

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
    public function showCheckout(Request $request, Store $store, $product_slug)
    {
            $id = \Illuminate\Support\Str::of($product_slug)->afterLast('-');
            $product = $store->products()->with('images')->findOrFail($id);
            $validated = $request->validate([
                'quantity' => 'required|numeric|min:0.5',
                'image_name' => 'nullable|string'
            ]);

            $quantity = $validated['quantity'];
            $selectedImageName = $request->query('image_name');
            $checkoutImage = null;
            if ($selectedImageName) {
                $checkoutImage = $product->images->first(function ($image) use ($selectedImageName) {
                    return basename($image->image_path) === $selectedImageName;
                });
            }
            if (!$checkoutImage) {
                $checkoutImage = $product->images->first();
            }
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
        $store = Auth::user()->store; 

    if (!$store) {
        return redirect()->route('store.profile.create')
            ->with('info', 'Anda harus memiliki toko untuk melihat riwayat penjualan.');
    }
    $query = \App\Models\Order::where('seller_id', $store->user_id)
                ->with(['buyer', 'orderItems.product.category'])
                ->latest();
    $penjualansForJs = $query->get()->map(function($order) {
        $firstItem = $order->orderItems->first();
        return [
            'order_id' => $order->id,
            'pembeli' => optional($order->buyer)->name ?? 'Pembeli Dihapus',
            'produk_list' => $order->orderItems->pluck('product.name')->join(', '),
            'kategori' => optional($order->orderItems->first()->product->category)->name ?? '-', 
            'jumlah_item' => $order->orderItems->sum('quantity'),
            'selling_unit' => optional($firstItem->product)->selling_unit,
            'total' => (int)$order->total_amount,
            'status' => $order->status,
            'translated_status' => $order->translated_status,
            'detailUrl' => route('marketplace.purchase.detail', ['order' => $order->order_number])
        ];
    });
        $completedQuery = \App\Models\OrderItem::query()
            ->join('products', 'order_items.product_id', '=', 'products.id') 
            ->where('products.store_id', $store->id) 
            ->whereHas('order', function ($q) {
                $q->where('status', 'completed');
            });

        $totalProduk = (int) (clone $completedQuery)->sum('order_items.quantity');
        $totalPenjualan = (clone $completedQuery)->sum(DB::raw('(order_items.price / products.weight_per_item) * order_items.quantity'));
        $salesLast7Days = (clone $completedQuery)
            ->where('order_items.created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(order_items.created_at) as date'),
                DB::raw('SUM((order_items.price / products.weight_per_item) * order_items.quantity) as total_sales')
            )
            ->groupBy('date')->orderBy('date', 'ASC')->get()->keyBy('date');

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->translatedFormat('d M'); 
            $chartData[] = $salesLast7Days[$date]->total_sales ?? 0;
        }

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
    public function edit($product_slug)
    {
        $id = \Illuminate\Support\Str::of($product_slug)->afterLast('-');
        $product = \App\Models\Product::findOrFail($id);
        if ($product->store_id !== Auth::user()->store->id) {
            abort(403, 'AKSES DITOLAK');
        }

        $kategoriList = ProductCategory::pluck('name')->all();
        $statusList = ['Tersedia', 'Diarsipkan'];
        return view('pages.marketplace.edit', [
            'product' => $product,
            'kategoriList' => $kategoriList,
            'statusList' => $statusList,
        ]);
    }

    /**
     * Memperbarui produk di database.
     */
    public function update(Request $request, $product_slug)
    {
        $id = \Illuminate\Support\Str::of($product_slug)->afterLast('-');
        $product = \App\Models\Product::findOrFail($id);

        if ($product->store_id !== Auth::user()->store->id) {
            abort(403);
        }
        $request->merge([
            'bobot' => str_replace(',', '.', $request->input('bobot'))
        ]);
        $request->merge(['nama' => Str::title($request->nama)]);
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

        $status = match($validatedData['status']) {
            'Diarsipkan' => 'draft',
            default => 'available',
        };
        if ((float)$validatedData['stok'] <= 0) {
            $status = 'sold';
        }

        $dataToUpdate = [
            'product_category_id' => $category->id,
            'name' => $validatedData['nama'],
            'price' => $validatedData['harga'],
            'stock' => $validatedData['stok'],
            'weight_per_item' => $validatedData['bobot'],
            'selling_unit' => $validatedData['satuan_berat'],
            'status' => $status, 
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
        if ($request->hasFile('gambar')) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }
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
        return Excel::download(new MultiSheetSalesExport(), $fileName);
    }
    public function showRatingForm(Order $order)
    {
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Akses Ditolak');
        }
        $review = StoreReview::where('order_id', $order->id)
                            ->where('user_id', Auth::id())
                            ->first();
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
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Akses Ditolak');
        }
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