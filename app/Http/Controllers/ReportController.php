<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
    public function store(Request $request)
    {
        // Validasi tetap di luar try-catch, agar error validasi tetap diarahkan otomatis
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Upload gambar
            $imagePath = $request->file('file')->store('report-images', 'public');

            // Simpan ke database
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

            DB::commit();

            return redirect()->route('lapor.index')
                ->with('success', 'Laporan Anda berhasil dikirim!');

        } catch (\Throwable $e) {
            DB::rollBack();

            // Hapus file yang sudah terupload (jika ada)
            if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Catat error ke log
            Log::error('Gagal menyimpan laporan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim laporan. Silakan coba lagi.');
        }
    }

}
