<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StoreController extends Controller
{
    /**
     * Menampilkan halaman toko dengan daftar produk toko tersebut.
     * Menggunakan Route Model Binding untuk mengambil Store.
     * * @param Store $store
     * @return \Illuminate\View\View
     */
    public function show(Store $store)
    {
        $totalSold = \App\Models\OrderItem::whereHas('product', function ($query) use ($store) {
            $query->where('store_id', $store->id);
        })->whereHas('order', function ($query) {
            $query->where('status', 'completed');
        })->sum('quantity');
        $store->load(['reviews', 'products' => function ($query) {
            $query->with(['category', 'images'])
                ->withSum(['orderItems as sold_count' => function ($subQuery) {
                    $subQuery->whereHas('order', function ($q) {
                        $q->where('status', 'completed');
                    });
                }], 'quantity');
        }]);

        $storeRating = $store->reviews->avg('rating');

        $productsFormatted = $store->products->map(function ($product) use ($store, $storeRating) {
            $productRating = $storeRating ?? 0.0; 
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => optional($product->category)->slug, 
                'price' => (int)$product->price,
                'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('img/placeholder.png'),
                'rating' => $productRating, 
                'sold' => (int)($product->sold_count ?? 0),
                'store' => $store->name,
            ];
        });

        $categories = ProductCategory::all()->map(function ($category) {
            return [
                'id' => $category->slug,
                'name' => $category->name,
                'icon' => $category->icon, 
            ];
        });

        return view('pages.marketplace.store', [
            'store' => $store,
            'products' => $productsFormatted,
            'categories' => $categories,
            'storeRating' => number_format($storeRating ?? 0, 1),
            'totalSold' => $totalSold,
        ]);
    }
}