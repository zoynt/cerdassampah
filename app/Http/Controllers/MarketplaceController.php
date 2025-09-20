<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        // =======================================================
        // PEMBUATAN DUMMY DATA UNTUK DEMO
        // =======================================================

        $kategoriList = collect(['Logam', 'Plastik', 'Kertas & Kardus']);

        $dummyProduk = new Collection();
        for ($i = 1; $i <= 55; $i++) {
            $kategori = $kategoriList->random();
            $dummyProduk->push((object)[
                'id' => $i,
                'nama' => 'Kaleng Cat',
                'kategori' => $kategori,
                'harga' => 3000,
                'terjual' => 10,
                'alamat' => 'Kayu tangi',
                'satuan_berat' => 'Kilogram',
            ]);
        }

        // Terapkan filter jika ada
        if ($request->filled('kategori')) {
            $dummyProduk = $dummyProduk->where('kategori', $request->kategori);
        }

        // Buat Paginator manual dari dummy data
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $dummyProduk->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $produks = new LengthAwarePaginator($currentPageItems, $dummyProduk->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(), 'pageName' => 'page',
        ]);

        return view('pages.marketplace.penjualan', [
            'produks' => $produks,
            'totalProduk' => 25, // Angka statis sesuai desain
            'totalPenjualan' => 55, // Angka statis sesuai desain
            'kategoriList' => $kategoriList,
        ]);
    }

        public function create()
    {
        // Menyiapkan data daftar kategori untuk dropdown di form
        $kategoriList = ['Plastik', 'Kertas & Kardus', 'Logam', 'Kaca', 'Lainnya'];

        return view('pages.marketplace.create', [
            'kategoriList' => $kategoriList
        ]);
    }

    /**
     * =======================================================
     * METHOD BARU UNTUK MENYIMPAN PRODUK BARU
     * =======================================================
     */
    public function store(Request $request)
    {
        // 1. Validasi semua data yang masuk dari form
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan_berat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Tambahkan user_id dari user yang sedang login
        $validatedData['user_id'] = auth()->id();

        // 3. Proses upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/products');
            $validatedData['image_path'] = str_replace('public/', '', $path);
        }

        // 4. Simpan data ke database menggunakan Model 'Produk'
        Produk::create($validatedData);

        // 5. Redirect ke halaman daftar produk dengan pesan sukses
        return redirect()->route('marketplace.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk)
    {
        // Keamanan: Pastikan user hanya bisa mengedit produk miliknya sendiri
        if ($produk->user_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }

        $kategoriList = ['Plastik', 'Kertas & Kardus', 'Logam', 'Kaca', 'Lainnya'];

        return view('pages.marketplace.edit', [
            'produk' => $produk,
            'kategoriList' => $kategoriList,
        ]);
    }

    /**
     * =======================================================
     * METHOD BARU UNTUK MEMPROSES UPDATE PRODUK
     * =======================================================
     */
    public function update(Request $request, Produk $produk)
    {
        // Keamanan: Pastikan user hanya bisa mengedit produk miliknya sendiri
        if ($produk->user_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan_berat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->image_path) {
                Storage::delete('public/' . $produk->image_path);
            }
            // Simpan gambar baru
            $path = $request->file('gambar')->store('public/products');
            $validatedData['image_path'] = str_replace('public/', '', $path);
        }

        $produk->update($validatedData);

        return redirect()->route('marketplace.produk')->with('success', 'Produk berhasil diperbarui!');
    }
}
