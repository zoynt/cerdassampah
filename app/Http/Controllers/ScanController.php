<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScanController extends Controller
{
    public function index()
    {
        return view('landing.scan-sampah');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        // 1. Simpan file di Laravel
        $path = $request->file('image')->store('uploads', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // 2. Kirim salinan ke Flask
        try {
            $response = Http::attach(
                'image', file_get_contents($fullPath), $request->file('image')->getClientOriginalName()
            )->post(env('FLASK_URL') . '/predict');

            if ($response->failed()) {
                return back()->with('error', 'Gagal menghubungi server AI.');
            }

            $data = $response->json();

            if (isset($data['label'])) {
                // Ambil data edukasi dari DB
                $info = WasteType::where('type_name', $data['label'])->first();

                return response()->json([
                    'imageUrl' => asset('storage/' . $path),
                    'title' => $data['label'],
                    'type' => $data['label'],  // Label jenis sampah
                    'handling' => [
                        'Kosongkan isi terlebih dahulu',
                        'Bersihkan jika memungkinkan',
                        'Pisahkan dari sampah organik sebelum dibuang'
                    ],
                    'recycling' => [
                        'Benang poliester untuk pakaian',
                        'Ubin lantai',
                        'Wadah plastik baru'
                    ]
                ]);
            } else {
                return back()->with('error', 'Tidak ada label yang dikembalikan.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
