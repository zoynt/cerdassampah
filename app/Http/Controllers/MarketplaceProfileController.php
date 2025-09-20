<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Pastikan Carbon di-import

class MarketplaceProfileController extends Controller
{
    /**
     * Menampilkan halaman ringkasan profil marketplace dengan DUMMY DATA.
     */
    public function show()
    {
        // =======================================================
        // PEMBUATAN DUMMY DATA UNTUK DEMO
        // =======================================================
        $marketplace = (object) [
            'nama_marketplace' => 'Toko Daur Ulang Barokah',
            'status' => true,
            'image_path' => null,
            'alamat_lengkap' => 'Jl. Pahlawan No. 123, Kel. Sejahtera',
            'kecamatan' => 'Banjarmasin Tengah',
            'kelurahan' => 'Sejahtera',
            'hari_operasional' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            'jam_mulai' => '08:00',
            'jam_berakhir' => '17:00',
            'nomor_telepon' => '081234567890', // <-- TAMBAHKAN BARIS INI
            'deskripsi' => 'Kami menerima dan membeli berbagai jenis sampah anorganik...',
        ];

        return view('pages.marketplace.profile-show', compact('marketplace'));
    }

    /**
     * Menampilkan form untuk mengedit profil marketplace.
     */
    public function edit()
    {
        $marketplace = Marketplace::firstOrNew(['user_id' => Auth::id()]);
        return view('pages.marketplace.profile-edit', compact('marketplace'));
    }

    /**
     * Menyimpan atau memperbarui profil marketplace.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'nama_marketplace' => 'required|string|max:255',
            'hari_operasional' => 'required|array|min:1',
            'alamat_lengkap' => 'required|string',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_berakhir' => 'required|date_format:H:i|after:jam_mulai',
            'nomor_telepon' => 'nullable|string|max:15',
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $marketplace = Marketplace::firstWhere('user_id', Auth::id());
            if ($marketplace && $marketplace->image_path) {
                // =======================================================
                // PERBAIKAN TYPO DI SINI
                // =======================================================
                Storage::delete('public/' . $marketplace->image_path);
            }
            $path = $request->file('gambar')->store('public/marketplaces');
            $validatedData['image_path'] = str_replace('public/', '', $path);
        }

        Marketplace::updateOrCreate(
            ['user_id' => Auth::id()],
            $validatedData
        );

        return redirect()->route('marketplace.profile.show')->with('success', 'Profil Marketplace berhasil disimpan!');
    }
}
