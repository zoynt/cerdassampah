<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankWasteCategory;
use App\Models\BankWasteProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Direkomendasikan untuk logging error

class WastePriceController extends Controller
{
    /**
     * Menampilkan daftar harga sampah untuk bank sampah milik pengelola yang login.
     */
    public function index(Request $request)
    {
        // [PERBAIKAN] Ambil bank berdasarkan pengguna yang sedang login.
        $bank = Auth::user()->bank;
        if (!$bank) {
            // Jika pengelola tidak terhubung ke bank sampah, tampilkan error.
            return redirect()->route('dashboard')->with('error', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu.');
        }

        // Ambil produk HANYA dari bank sampah milik pengelola.
        $query = $bank->wasteProducts()->with('category');

        // Fitur Filter (jika diperlukan)
        if ($search = $request->input('search')) {
            $query->where('item_name', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $products = $query->latest()->get();

        // Kalkulasi ini sekarang otomatis hanya menghitung data dari bank tersebut.
        $totalItem = $products->count();
        $hargaAktif = $products->where('status', 'Aktif')->count();
        $rataRataHarga = $products->avg('price_per_kg');
        
        // Ambil master kategori untuk form "Tambah Item".
        $categories = BankWasteCategory::orderBy('name')->get();

        return view('pages.banksampah.pengelola.harga.index', compact(
            'products', 
            'totalItem', 
            'hargaAktif', 
            'rataRataHarga', 
            'categories'
            // Variabel 'bank' tidak perlu dikirim lagi karena bisa diakses via Auth::user()->bank
        ));
    }

    /**
     * Menyimpan item sampah baru ke bank sampah milik pengelola yang login.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'waste_category_id' => 'required|exists:bank_waste_categories,id',
            'item_name'         => 'required|string|max:255',
            'price_per_kg'      => 'required|numeric|min:0',
            'description'       => 'nullable|string|max:255',
            'status'            => 'required|in:Aktif,Tidak Aktif',
        ]);
        
        // [PERBAIKAN] Ambil bank berdasarkan pengguna yang sedang login.
        $bank = Auth::user()->bank;
        if (!$bank) {
            return back()->with('error', 'Anda tidak terdaftar sebagai pengelola.');
        }

        // Cek duplikasi spesifik untuk bank ini.
        $exists = $bank->wasteProducts()
                       ->where('waste_category_id', $validatedData['waste_category_id'])
                       ->where('item_name', $validatedData['item_name'])
                       ->exists();
                       
        if ($exists) {
            $categoryName = BankWasteCategory::find($validatedData['waste_category_id'])->name ?? 'Kategori tidak dikenal';
            return back()->with('error', 'Item "'.$validatedData['item_name'].'" dalam kategori "'.$categoryName.'" sudah ada.')->withInput();
        }

        // Relasi $bank->wasteProducts() akan otomatis mengisi bank_id yang benar.
        // Kita juga tambahkan 'item_name' dari validasi
        $bank->wasteProducts()->create($validatedData);

        return redirect()->route('pengelola.harga.index')->with('success', 'Item sampah baru berhasil ditambahkan.');
    }
    
    /**
     * Memperbarui data item sampah yang ada.
     */
    public function update(Request $request, BankWasteProduct $product)
    {
        // [KEAMANAN] Pastikan produk ini milik bank sampah si pengelola.
        if ($product->bank_id !== Auth::user()->bank_id) {
            abort(403, 'ANDA TIDAK MEMILIKI IZIN UNTUK MENGUBAH DATA INI.');
        }

        $validatedData = $request->validate([
            'waste_category_id' => 'required|exists:bank_waste_categories,id',
            'item_name'         => 'required|string|max:255',
            'price_per_kg'      => 'required|numeric|min:0',
            'description'       => 'nullable|string|max:255',
            'status'            => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Cek duplikasi jika nama item atau kategori diubah.
        $exists = Auth::user()->bank->wasteProducts()
                   ->where('waste_category_id', $validatedData['waste_category_id'])
                   ->where('item_name', $validatedData['item_name'])
                   ->where('id', '!=', $product->id) // Abaikan item yang sedang diedit
                   ->exists();

        if ($exists) {
            return back()->with('error', 'Kombinasi kategori dan nama item tersebut sudah ada.');
        }

        // Update data produk.
        $product->update($validatedData);
        
        return redirect()->route('pengelola.harga.index')->with('success', 'Harga item sampah berhasil diperbarui.');
    }

    /**
     * Menghapus item sampah dari daftar harga.
     */
    public function destroy(BankWasteProduct $product)
    {
        // [KEAMANAN] Pastikan produk ini milik bank sampah si pengelola.
        if ($product->bank_id !== Auth::user()->bank_id) {
            abort(403, 'ANDA TIDAK MEMILIKI IZIN UNTUK MENGHAPUS DATA INI.');
        }

        try {
            // Cek apakah produk ini pernah dipakai di transaksi (opsional tapi disarankan)
            if ($product->transactionDetails()->exists()) {
                 return back()->with('error', 'Gagal menghapus. Item ini sudah pernah digunakan dalam transaksi.');
            }
            
            $product->delete();
            return redirect()->route('pengelola.harga.index')->with('success', 'Item sampah berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus item harga: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus item. Mungkin item ini terkait dengan data lain.');
        }
    }
}