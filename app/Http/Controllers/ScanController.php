<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Scan;
use App\Models\WasteType;
use App\Http\Controllers\UserQuestController;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function scan(Request $request)
    {
        $request->validate(['image' => 'required|image']);

        $imagePath = $request->file('image')->getPathname();
        $storedPath = $request->file('image')->store('uploads', 'public');

        try {
            $response = Http::timeout(60)->attach(
                'image',
                file_get_contents($imagePath),
                $request->file('image')->getClientOriginalName()
            )->post(env('FLASK_URL') . '/predict');

            if ($response->failed()) {
                return response()->json(['error' => 'Server AI gagal memproses gambar.'], 502);
            }
            $flaskData = $response->json();
        } catch (ConnectionException $e) {
            report($e);
            return response()->json(['error' => 'Tidak dapat terhubung ke server AI.'], 504);
        }

        $label = $flaskData['label'] ?? null;
        if (!$label) {
            return response()->json(['error' => 'Jenis sampah tidak terdeteksi.'], 422);
        }
        $confidence = $flaskData['confidence'] ?? null;
        
        $normalizedLabel = strtolower($label);
        $info = WasteType::with('materials')
                         ->where(DB::raw('LOWER(type_name)'), $normalizedLabel)
                         ->first();

        $recyclingMethods = [];
        $description_mat = 'Deskripsi untuk sampah ini belum tersedia.';
        $suggest = [];
        $questResult = null; 

        if ($info) {
            try {
                if ($info->materials->isNotEmpty()) {
                    $recyclingMethods = $info->materials->pluck('recycle_info')->filter()->values()->all();
                    
                    $descriptions = $info->materials->pluck('description_mat')->filter()->values();
                    if ($descriptions->isNotEmpty()) {
                        $description_mat = $descriptions->random();
                    }
                    
                    $suggest = $info->materials->pluck('suggest')->filter()->unique()->values()->all();
                }

                $questResult = UserQuestController::tryCompleteScanQuest($info->id);
                
                // Cek apakah UserQuestController mengembalikan pesan error dari transaksi
                if (isset($questResult['error'])) {
                    // Jika ya, teruskan pesan error tersebut ke frontend
                    return response()->json(['error' => $questResult['message']], 500);
                }

            } catch (Throwable $e) {
                // Tangkap error tak terduga lainnya
                report($e);
                return response()->json(['error' => 'Terjadi kesalahan sistem saat memproses data.'], 500);
            }
        }

        $data = [
            'label'         => ucfirst($label),
            'confidence'    => $confidence,
            'description'   => $description_mat,
            'recycling'     => $recyclingMethods,
            'suggest'       => $suggest,
            'imageUrl'      => asset('storage/' . $storedPath),
            'questResult'   => $questResult, 
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
