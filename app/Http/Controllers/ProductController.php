<?php

namespace App\Http\Controllers;

use App\Models\Penjualan; // Pastikan ini ditambahkan
use App\Models\Produk;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Pastikan Carbon di-import

class ProductController extends Controller
{
    /**
     * Menampilkan halaman daftar produk.
     */
    public function index(Request $request)
    {
        // =======================================================
        // PEMBUATAN DUMMY DATA UNTUK DEMO
        // =======================================================

        $kategoriList = collect(['Logam', 'Plastik', 'Kertas & Kardus']);

        $dummyProduk = new Collection();
        for ($i = 1; $i <= 25; $i++) {
            $kategori = $kategoriList->random();
            $nama = ($kategori == 'Logam') ? 'Kaleng Cat' : (($kategori == 'Plastik') ? 'Botol Plastik' : 'Kardus Bekas');
            $dummyProduk->push((object)[
                'id' => $i,
                'nama' => $nama,
                'kategori' => $kategori,
                'harga' => rand(2000, 5000),
                'stok' => rand(5, 20),
                'alamat' => 'Kayu tangi',
                'satuan_berat' => 'Kilogram',
            ]);
        }

        // Terapkan filter jika ada
        if ($request->filled('kategori')) {
            $dummyProduk = $dummyProduk->where('kategori', $request->kategori);
        }

        // Buat Paginator manual dari dummy data
        $perPage = 10; // Menampilkan 10 item per halaman
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $dummyProduk->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $produks = new LengthAwarePaginator($currentPageItems, $dummyProduk->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(), 'pageName' => 'page',
        ]);

        return view('pages.marketplace.produk', [
            'produks' => $produks,
            'kategoriList' => $kategoriList,
        ]);
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create()
    {
        $kategoriList = ['Plastik', 'Kertas & Kardus', 'Logam', 'Kaca', 'Lainnya'];
        // Status untuk produk baru
        $statusList = ['Tersedia', 'Pending'];

        return view('pages.marketplace.create', [
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
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'bobot' => 'required|string|max:255',
            'satuan_berat' => 'required|string',
            'status' => 'required|string|in:Tersedia,Pending,Habis',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validatedData['user_id'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/products');
            $validatedData['image_path'] = str_replace('public/', '', $path);
        }

        Produk::create($validatedData);

        return redirect()->route('marketplace.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * =======================================================
     * METHOD BARU UNTUK MENAMPILKAN FORM EDIT
     * =======================================================
     */
    public function edit(Produk $produk)
    {
        if ($produk->user_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }

        $kategoriList = ['Plastik', 'Kertas & Kardus', 'Logam', 'Kaca', 'Lainnya'];
        // Status untuk edit produk
        $statusList = ['Tersedia', 'Pending', 'Habis'];

        return view('pages.marketplace.edit', [
            'produk' => $produk,
            'kategoriList' => $kategoriList,
            'statusList' => $statusList,
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        if ($produk->user_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'bobot' => 'required|string|max:255',
            'satuan_berat' => 'required|string',
            'status' => 'required|string|in:Tersedia,Pending,Habis',
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
        public function riwayatPenjualan(Request $request)
    {
        // =======================================================
        // PEMBUATAN DUMMY DATA UNTUK DEMO
        // =======================================================

        $kategoriList = collect(['Logam', 'Plastik', 'Kertas & Kardus']);

        $dummyPenjualan = new Collection();
        for ($i = 1; $i <= 30; $i++) {
            $harga = 3000;
            $terjual = rand(5, 15);
            $dummyPenjualan->push((object)[
                'id' => $i,
                'pembeli' => 'Asih',
                'produk' => 'Kaleng Cat',
                'kategori' => 'Logam',
                'harga' => $harga,
                'terjual' => $terjual,
                'total' => $harga * $terjual,
                'status' => 'Selesai',
            ]);
        }

        // Terapkan filter jika ada
        if ($request->filled('kategori')) {
            $dummyPenjualan = $dummyPenjualan->where('kategori', $request->kategori);
        }

        // Buat Paginator manual
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $dummyPenjualan->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $penjualans = new LengthAwarePaginator($currentPageItems, $dummyPenjualan->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(), 'pageName' => 'page',
        ]);

        return view('pages.marketplace.riwayat', [
            'penjualans' => $penjualans,
            'kategoriList' => $kategoriList,
        ]);
    }

    public function showPenjualan($penjualanId) // Menerima ID, bukan model
    {
        // =======================================================
        // PEMBUATAN DUMMY DATA UNTUK DEMO
        // =======================================================

        // 1. Buat data produk palsu yang terkait dengan penjualan ini
        $dummyProduk = (object) [
            'nama' => 'Kaleng Cat',
            'kategori' => 'Logam',
            'harga' => 3000,
            'satuan_berat' => 'Kilogram',
            'alamat' => 'Kayu Tangi, Banjarmasin',
            // Tambahkan latitude & longitude untuk peta
            'latitude' => -3.316694,
            'longitude' => 114.590111,
        ];

        // 2. Buat data penjualan palsu
        $dummyPenjualan = (object) [
            'id' => $penjualanId,
            'pembeli' => 'Asih',
            'jumlah_terjual' => 10,
            'total_harga' => 30000,
            'status' => 'Selesai',
            'created_at' => Carbon::now()->subDays(2),
            'produk' => $dummyProduk, // Hubungkan produk palsu ke penjualan palsu
        ];

        // Keamanan (opsional untuk dummy, tapi praktik yang baik)
        // if ($dummyPenjualan->user_id !== auth()->id()) {
        //     abort(403);
        // }

        return view('pages.marketplace.detail-pembelian', [
            'penjualan' => $dummyPenjualan
        ]);
    }

        public function createProfile()
    {
        // Cari profil yang sudah ada, atau buat instance baru jika belum ada
        $marketplace = Marketplace::firstOrNew(['user_id' => auth()->id()]);

        return view('pages.marketplace.profile', [
            'marketplace' => $marketplace
        ]);
    }

    /**
     * Menyimpan atau memperbarui profil marketplace.
     */
    public function storeProfile(Request $request)
    {
        $validatedData = $request->validate([
            'nama_marketplace' => 'required|string|max:255',
            'hari_operasional' => 'required|array|min:1',
            'hari_operasional.*' => 'string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_berakhir' => 'required|date_format:H:i|after:jam_mulai',
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/marketplaces');
            $validatedData['image_path'] = str_replace('public/', '', $path);
        }

        // Gunakan updateOrCreate untuk membuat jika belum ada, atau update jika sudah ada
        Marketplace::updateOrCreate(
            ['user_id' => auth()->id()],
            $validatedData
        );

        return redirect()->back()->with('success', 'Profil Marketplace berhasil disimpan!');
    }
}
