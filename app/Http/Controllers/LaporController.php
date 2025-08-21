<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // ✅ Hanya satu kali dan di atas
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporController extends Controller
{
    public function index()
    {
        return view('pages.report.lapor'); // ✅ View berada di resources/views/landing/lapor.blade.php
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'file' => 'nullable|image|max:2048'
        ]);

        // Simpan file jika ada
        $imagePath = null;
        if ($request->hasFile('file')) {
            $imagePath = $request->file('file')->store('uploads', 'public');
        }

        // Simpan ke database
        Report::create([
            'user_id' => Auth::id(), // atau null jika belum login
            'name' => $validated['name'],
            'email' => $validated['email'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $validated['address'],
            'status' => 'pending',
            'image' => $imagePath,
            'waktu_lapor' => now(),
            'waktu_selesai' => null
        ]);

        // Redirect ke halaman landing dengan notifikasi sukses
        return redirect('/')
            ->with('success', 'Laporan berhasil dikirim. Terima kasih!');
    }
}
