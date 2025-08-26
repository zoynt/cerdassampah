<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Menampilkan form laporan untuk pengguna yang sudah login.
     */
    public function index()
    {
        return view('pages.report.lapor');
    }

    /**
     * Menyimpan laporan baru dari pengguna yang sudah login.
     */
     public function history(Request $request)
    {
        $search = $request->input('search');

        // Ambil laporan HANYA milik user yang sedang login
        $reports = Report::where('user_id', Auth::id())
                        ->when($search, function ($query, $search) {
                            // Logika pencarian berdasarkan alamat atau status
                            return $query->where('address', 'like', "%{$search}%")
                                         ->orWhere('status', 'like', "%{$search}%");
                        })
                        ->orderBy('waktu_lapor', 'desc') 
                        ->paginate(5); 

        // Mengarahkan ke view yang benar sesuai struktur folder Anda
        return view('pages.report.history', compact('reports', 'search'));
    }
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'file' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // 2. Proses upload file gambar
        $imagePath = $request->file('file')->store('report-images', 'public');

        // 3. Buat laporan baru di database, user_id pasti ada.
        Report::create([
            'user_id' => Auth::id(),
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'address' => $validatedData['address'],
            'image' => $imagePath,
            'status' => 'pending',
            'waktu_lapor' => now(),
        ]);

        // 4. Arahkan kembali ke halaman laporan di dalam dasbor dengan pesan sukses
        return redirect()->route('lapor.index')->with('success', 'Laporan Anda berhasil dikirim!');
    }
}