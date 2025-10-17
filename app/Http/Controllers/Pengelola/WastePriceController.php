<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankWasteCategory;
use App\Models\BankWasteProduct;
use App\Models\BankTransaction; // <--- TAMBAHKAN INI
use App\Models\BankTransactionDetail; // <--- TAMBAHKAN INI JUGA
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Pastikan ini ada untuk membuat slug

class WastePriceController extends Controller
{
// app/Http/Controllers/Pengelola/WastePriceController.php

    public function index(Request $request)
    {
        // [MODE DEVELOPMENT] Mengambil bank sampah pertama yang ada di database.
        $bank = Bank::first(); 
        
        if (!$bank) {
            abort(500, 'Tidak ada data bank sampah di dalam database.');
        }

        $query = $bank->wasteProducts()->with('category');

        // Fitur Filter (jika diperlukan di masa depan)
        if ($search = $request->input('search')) {
            $query->whereHas('category', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->get();

        // [KODE YANG BENAR] Kalkulasi untuk summary cards halaman harga sampah
        $totalItem = $products->count();
        $hargaAktif = $products->where('status', 'Aktif')->count();
        $rataRataHarga = $products->avg('price_per_kg');
        
        // Ambil master kategori untuk form "Tambah Item"
        $categories = BankWasteCategory::orderBy('name')->get();

        // [KODE YANG BENAR] Panggil view yang benar dengan data yang benar
        return view('pages.banksampah.pengelola.harga.index', compact(
            'products', 
            'totalItem', 
            'hargaAktif', 
            'rataRataHarga', 
            'categories',
            'bank'
        ));
    }

    /**
     * Menampilkan halaman detail untuk satu setoran.
     *
     * @param  \App\Models\BankTransactionDetail  $setoran
     * @return \Illuminate\View\View
     */
    public function show(BankTransactionDetail $setoran)
    {
        // Eager load semua relasi yang dibutuhkan untuk halaman detail.
        // Ini akan mengambil data pengguna, produk sampah, dan transaksi induknya secara efisien.
        $setoran->load(['transaction.rekening.user', 'wasteProduct']);
    
        // Kirim data setoran tunggal ke view 'show'
        return view('pages.banksampah.pengelola.riwayat.show', compact('setoran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'waste_category_id' => 'required', 
            'price_per_kg' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);
        
        // [MODE DEVELOPMENT] Ambil bank sampah pertama yang ada di database.
        $bank = Bank::first();
        if (!$bank) {
            return back()->with('error', 'Tidak ada data bank sampah di dalam database.');
        }

        $categoryInput = $request->waste_category_id;
        $category = null;

        // Cek apakah input adalah ID numerik atau nama baru
        if (is_numeric($categoryInput)) {
            $category = BankWasteCategory::find($categoryInput);
        } else {
            // Jika string, cari atau buat kategori baru.
            $category = BankWasteCategory::firstOrCreate(
                ['name' => $categoryInput],
                ['slug' => Str::slug($categoryInput)] 
            );
        }
        
        if (!$category) {
            return back()->with('error', 'Kategori sampah yang dipilih tidak valid.');
        }
        
        $exists = $bank->wasteProducts()->where('waste_category_id', $category->id)->exists();
        if ($exists) {
            return back()->with('error', 'Jenis sampah ini sudah ada di daftar harga Anda.');
        }

        // Simpan data produk baru, termasuk 'item_name'
        $bank->wasteProducts()->create([
            'item_name'         => $category->name,
            'waste_category_id' => $category->id,
            'price_per_kg'      => $request->price_per_kg,
            'description'       => $request->description,
            'status'            => $request->status,
        ]);

        return redirect()->route('pengelola.harga.index')->with('success', 'Item sampah berhasil ditambahkan.');
    }
    
    public function update(Request $request, BankWasteProduct $product)
    {
        // [MODE DEVELOPMENT] Pengecekan keamanan dinonaktifkan sementara.
        // $this->authorize('update', $product);

        $request->validate([
            'price_per_kg' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $product->update($request->all());
        
        return redirect()->route('pengelola.harga.index')->with('success', 'Harga berhasil diperbarui.');
    }

    public function destroy(BankWasteProduct $product)
    {
        // [MODE DEVELOPMENT] Pengecekan keamanan dinonaktifkan sementara.
        // $this->authorize('delete', $product);

        try {
            $product->delete();
            return redirect()->route('pengelola.harga.index')->with('success', 'Item sampah berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus item.');
        }
    }
}