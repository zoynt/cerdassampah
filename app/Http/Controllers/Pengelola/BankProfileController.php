<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BankProfileController extends Controller
{
    /**
     * [BARU] Menampilkan halaman profil publik (Info Detail) bank sampah.
     * Diadaptasi dari profile-show.blade.php
     */
    public function show(Bank $bank)
    {
        return view('pages.banksampah.profil-show', [
            'bank' => $bank
        ]);
    }

    /**
     * [BARU] Menampilkan halaman daftar item yang diterima bank sampah.
     * Diadaptasi dari store.blade.php
     */
    public function indexItems(Bank $bank)
    {
        // Mengambil data item sampah yang diterima bank ini
        $products = $bank->wasteProducts()->where('status', 'Aktif')->with('category')->get();
        
        // Menyiapkan data produk untuk Alpine.js
        $productsForAlpine = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->item_name,
                'price' => $product->price_per_kg,
                'category_id' => $product->waste_category_id,
                'category_name' => $product->category->name ?? 'N/A',
                'image' => asset('img/placeholder-sampah.png'), // Ganti dengan gambar item jika ada
            ];
        });

        // Mengambil kategori unik dari produk yang ada
        $categories = $products->map(function ($product) {
            if ($product->category) {
                return [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ];
            }
            return null;
        })->filter()->unique('id')->values();

        return view('pages.banksampah.item-index', [
            'bank' => $bank,
            'products' => $productsForAlpine,
            'categories' => $categories,
        ]);
    }

    /**
     * Menampilkan form untuk pengelola mengedit profil bank sampahnya.
     */
    public function edit()
    {
        $bank = Auth::user()->bank;
        if (!$bank) {
            $bank = new Bank(); // Siapkan untuk membuat bank baru
        }
        return view('pages.banksampah.pengelola.profil.profil-edit', compact('bank'));
    }

    /**
     * Menyimpan atau memperbarui profil bank sampah.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'operational_days' => 'nullable|array',
            'address' => 'required|string',
            'district' => 'required|string|max:255',
            'sub_district' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'opening_hour' => 'nullable|date_format:H:i',
            'closing_hour' => 'nullable|date_format:H:i|after_or_equal:opening_hour',
            'phone_number' => 'required|string|max:20',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        $bank = Auth::user()->bank;
        if (!$bank) {
            $bank = new Bank();
            $bank->user_id = Auth::id();
        }

        if ($request->hasFile('image_path')) {
            if ($bank->image_path) {
                Storage::disk('public')->delete($bank->image_path);
            }
            $validatedData['image_path'] = $request->file('image_path')->store('bank-images', 'public');
        }

        $updateData = $validatedData;
        $updateData['slug'] = Str::slug($validatedData['bank_name']);
        $updateData['is_active'] = $request->input('status', $bank->is_active ?? true);

        $bank->fill($updateData);
        $bank->save();

        // Redirect ke halaman profil publik yang baru saja diupdate
        return redirect()->route('bank-sampah.profil.show', $bank->slug)->with('success', 'Profil bank sampah berhasil diperbarui!');    }
}