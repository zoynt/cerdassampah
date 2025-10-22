<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankWasteCategory;
use App\Models\BankWasteProduct; // Model Anda sudah benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WastePriceController extends Controller
{
    /**
     * Menampilkan daftar harga sampah untuk bank sampah milik pengelola yang login.
     * (Logika ini sudah benar)
     */
    public function index(Request $request)
    {
        $bank = Auth::user()->bank; // Mengambil bank milik user
        if (!$bank) {
            return redirect()->route('pengelola.bank-profil.edit')->with('warning', 'Anda harus melengkapi profil bank sampah Anda terlebih dahulu untuk mengelola harga.');
        }

        // Hanya mengambil produk dari bank milik user
        $query = $bank->wasteProducts()->with('category');

        // Fitur Filter
        if ($search = $request->input('search')) {
            $query->where('item_name', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $products = $query->latest()->get();

        // Statistik
        $totalItem = $products->count();
        $hargaAktif = $products->where('status', 'Aktif')->count();
        $rataRataHarga = $products->avg('price_per_kg');
        
        $categories = BankWasteCategory::orderBy('name')->get();

        return view('pages.banksampah.pengelola.harga.index', compact(
            'products', 
            'totalItem', 
            'hargaAktif', 
            'rataRataHarga', 
            'categories'
        ));
    }

    /**
     * Menyimpan item sampah baru ke bank sampah milik pengelola yang login.
     * (Logika ini sudah benar)
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
        
        $bank = Auth::user()->bank;
        if (!$bank) {
            return back()->with('error', 'Anda tidak terdaftar sebagai pengelola.');
        }

        // Cek duplikasi spesifik untuk bank ini
        $exists = $bank->wasteProducts()
                       ->where('waste_category_id', $validatedData['waste_category_id'])
                       ->where('item_name', $validatedData['item_name'])
                       ->exists();
                       
        if ($exists) {
            $categoryName = BankWasteCategory::find($validatedData['waste_category_id'])->name ?? 'Kategori tidak dikenal';
            return back()->with('error', 'Item "'.$validatedData['item_name'].'" dalam kategori "'.$categoryName.'" sudah ada.')->withInput();
        }

        // Relasi ini akan OTOMATIS mengisi bank_id yang benar (ID 6)
        $bank->wasteProducts()->create($validatedData);

        return redirect()->route('pengelola.harga.index')->with('success', 'Item sampah baru berhasil ditambahkan.');
    }
    
    /**
     * Memperbarui data item sampah yang ada.
     * (Logika ini sudah benar)
     */
    public function update(Request $request, BankWasteProduct $product)
    {
        // [KEAMANAN YANG SUDAH BENAR]
        // Pengecekan ini adalah penyebab 403 jika datanya tidak cocok.
        if ($product->bank_id !== Auth::user()->bank->id) {
            abort(403, 'ANDA TIDAK MEMILIKI IZIN UNTUK MENGUBAH DATA INI.');
        }

        $validatedData = $request->validate([
            'waste_category_id' => 'required|exists:bank_waste_categories,id',
            'item_name'         => 'required|string|max:255',
            'price_per_kg'      => 'required|numeric|min:0',
            'description'       => 'nullable|string|max:255',
            'status'            => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Cek duplikasi jika nama item atau kategori diubah
        $exists = Auth::user()->bank->wasteProducts()
                      ->where('waste_category_id', $validatedData['waste_category_id'])
                      ->where('item_name', $validatedData['item_name'])
                      ->where('id', '!=', $product->id) // Abaikan item yang sedang diedit
                      ->exists();

        if ($exists) {
            return back()->with('error', 'Kombinasi kategori dan nama item tersebut sudah ada.');
        }

        $product->update($validatedData);
        
        return redirect()->route('pengelola.harga.index')->with('success', 'Harga item sampah berhasil diperbarui.');
    }

    /**
     * Menghapus item sampah dari daftar harga.
     * (Logika ini sudah benar)
     */
    public function destroy(BankWasteProduct $product)
    {
        // [KEAMANAN YANG SUDAH BENAR]
        if ($product->bank_id !== Auth::user()->bank->id) {
            abort(403, 'ANDA TIDAK MEMILIKI IZIN UNTUK MENGHAPUS DATA INI.');
        }

        try {
            // Pengecekan ini bagus untuk integritas data
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