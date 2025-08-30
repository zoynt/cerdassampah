<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Scan;
use App\Models\WasteType;
use App\Models\Material;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        // Simpan gambar sementara untuk dikirim ke Flask
        $imagePath = $request->file('image')->getPathname();
        $storedPath = $request->file('image')->store('uploads', 'public');

        // Kirim ke Flask server
            try {
                $response = Http::timeout(60)->attach(
                    'image',
                    file_get_contents($imagePath),
                    $request->file('image')->getClientOriginalName()
                )->post(env('FLASK_URL') . '/predict');

                if ($response->failed()) {
                    // Jika Flask mengembalikan error (spt 4xx atau 5xx)
                    return response()->json(['error' => 'Server AI gagal memproses gambar.'], 502);
                }

                $flaskData = $response->json();

            } catch (ConnectionException $e) {
                // Jika tidak bisa terhubung ke Flask (timeout, server mati, dll)
                // Cek log laravel.log untuk detail errornya
                report($e); // Ini akan mencatat error ke log
                return response()->json(['error' => 'Tidak dapat terhubung ke server AI. Silakan coba lagi nanti.'], 504);
            }

        $flaskData = $response->json();
        $label = $flaskData['label'] ?? null;
        $confidence = $flaskData['confidence'] ?? null;

        $info = WasteType::with('materials')->where('type_name', $label)->first();
        // Ambil info dari DB
        // $info = WasteType::where('type_name', $label)->first();
        $recyclingMethods = [];
      if ($info && $info->materials->isNotEmpty()) {
          // Ganti 'name' dengan nama kolom yang sesuai di tabel 'materials' Anda
          // contohnya: 'recycling_method', 'description', dll.
          $recyclingMethods = $info->materials->pluck('recycle_info')->all();
      }
        $description_mat = $info->materials->pluck('description_mat')->random();

        // Susun data untuk dikirim kembali ke browser
        $data = [
            'label'       => ucfirst($label),
            'confidence'  => $confidence,
            // 'description' => $info->waste_description ?? 'Deskripsi belum tersedia',
            'description'   => $description_mat, // <-- TAMBAHKAN HURUF 'g' DI SINI
            'recycling'   => $recyclingMethods, // <-- TAMBAHKAN HURUF 'g' DI SINI
            // 'recycle'     => $recyclingMethods ?? '-',
            'imageUrl'    => asset('storage/' . $storedPath),
        ];

        return response()->json($data);
    }


// public function scan(Request $request)
// {
//     $request->validate([
//         'image' => 'required|image'
//     ]);

//     // 1. Simpan file di Laravel
//     $path = $request->file('image')->store('uploads', 'public');
//     $fullPath = storage_path('app/public/' . $path);

//     // 2. Kirim salinan ke Flask
//     $response = Http::attach(
//         'image', file_get_contents($fullPath), $request->file('image')->getClientOriginalName()
//     )->post(env('FLASK_URL') . '/predict');

//     if ($response->failed()) {
//         return back()->with('error', 'Gagal menghubungi server AI.');
//     }

//     $label = $response->json()['label'] ?? null;
//     // $confidence = $response->json()['confidence'] ?? null;

//     if (!$label) {
//         return back()->with('error', 'Tidak ada label yang dikembalikan.');
//     }

//     // 3. Ambil data edukasi dari DB
//     $info = WasteType::where('type_name', $label)->first();

//     return view('scanner', [
//         'label' => $label,
//         // 'confidence' => $confidence ? round($confidence * 100, 2) . '%' : '-',
//         'description' => $info->waste_description ?? 'Deskripsi belum tersedia',
//         'recycle' => $info->cara_daur_ulang ?? '-',
//         'impact' => $info->dampak ?? '-',
//         'imageUrl' => asset('storage/' . $path)
//     ]);
// }



    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Scan $scan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scan $scan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scan $scan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scan $scan)
    {
        //
    }
}
