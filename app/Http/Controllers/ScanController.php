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

    // public function scan(Request $request)
    // {
    //     $request->validate(['file' => 'required|file']);

    //     $imagePath = $request->file('file')->getPathname();
    //     $storedPath = $request->file('file')->store('uploads', 'public');

    //     try {
    //         $response = Http::timeout(60)->attach(
    //             'file',
    //             file_get_contents($imagePath),
    //             $request->file('file')->getClientOriginalName()
    //         )->post(env('FLASK_URL') . '/predict');

    //         if ($response->failed()) {
    //             return response()->json(['error' => 'Server AI gagal memproses gambar.'], 502);
    //         }
    //         $flaskData = $response->json();
    //     } catch (ConnectionException $e) {
    //         report($e);
    //         return response()->json(['error' => 'Tidak dapat terhubung ke server AI.'], 504);
    //     }

    //     $label = $flaskData['label'] ?? null;
    //     if (!$label) {
    //         return response()->json(['error' => 'Jenis sampah tidak terdeteksi.'], 422);
    //     }
    //     $confidence = $flaskData['confidence'] ?? null;
        
    //     $normalizedLabel = strtolower($label);
    //     $info = WasteType::with('materials')
    //                      ->where(DB::raw('LOWER(type_name)'), $normalizedLabel)
    //                      ->first();

    //     $recyclingMethods = [];
    //     $description_mat = 'Deskripsi untuk sampah ini belum tersedia.';
    //     $suggest = [];
    //     $questResult = null; 

    //     if ($info) {
    //         try {
    //             if ($info->materials->isNotEmpty()) {
    //                 $recyclingMethods = $info->materials->pluck('recycle_info')->filter()->values()->all();
                    
    //                 $descriptions = $info->materials->pluck('description_mat')->filter()->values();
    //                 if ($descriptions->isNotEmpty()) {
    //                     $description_mat = $descriptions->random();
    //                 }
                    
    //                 $suggest = $info->materials->pluck('suggest')->filter()->unique()->values()->all();
    //             }

    //             $questResult = UserQuestController::tryCompleteScanQuest($info->id);
                
    //             // Cek apakah UserQuestController mengembalikan pesan error dari transaksi
    //             if (isset($questResult['error'])) {
    //                 // Jika ya, teruskan pesan error tersebut ke frontend
    //                 return response()->json(['error' => $questResult['message']], 500);
    //             }

    //         } catch (Throwable $e) {
    //             // Tangkap error tak terduga lainnya
    //             report($e);
    //             return response()->json(['error' => 'Terjadi kesalahan sistem saat memproses data.'], 500);
    //         }
    //     }

    //     $data = [
    //         'label'         => ucfirst($label),
    //         'confidence'    => $confidence,
    //         'description'   => $description_mat,
    //         'recycling'     => $recyclingMethods,
    //         'suggest'       => $suggest,
    //         'imageUrl'      => asset('storage/' . $storedPath),
    //         'questResult'   => $questResult, 
    //     ];

    //     return response()->json($data);
    // }

    // public function scan(Request $request)
    // {
    //     // --- 1. Validasi yang lebih kuat ---
    //     // Memastikan file adalah gambar, tipenya sesuai, dan ukurannya tidak lebih dari 5MB.
    //     $request->validate([
    //         'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
    //     ]);

    //     // Simpan file yang diunggah untuk mendapatkan URL yang bisa diakses publik
    //     $storedPath = $request->file('file')->store('uploads', 'public');

    //     try {
    //         // --- 2. Kirim ke Server AI (Flask) ---
    //         $response = Http::timeout(60)->attach(
    //             'file',
    //             file_get_contents($request->file('file')->getRealPath()),
    //             $request->file('file')->getClientOriginalName()
    //         )->post(env('FLASK_URL') . '/predict');

    //         if ($response->failed()) {
    //             return response()->json(['success' => false, 'message' => 'Server AI gagal memproses gambar.'], 502);
    //         }
            
    //         $flaskData = $response->json();

    //     } catch (\Illuminate\Http\Client\ConnectionException $e) {
    //         report($e);
    //         return response()->json(['success' => false, 'message' => 'Tidak dapat terhubung ke server AI.'], 504);
    //     }

    //     // --- 3. Proses Respons dari Flask ---
    //     // Cek apakah ada array 'predictions' di dalam respons Flask
    //     if (!isset($flaskData['predictions']) || !is_array($flaskData['predictions'])) {
    //         return response()->json(['success' => false, 'message' => 'Format respons dari server AI tidak valid.'], 422);
    //     }

    //     $processedPredictions = [];

    //     // Loop melalui setiap prediksi yang dikembalikan oleh Flask
    //     foreach ($flaskData['predictions'] as $prediction) {
    //         $label = $prediction['class_name'] ?? null;
    //         $confidence = $prediction['confidence'] ?? 0;

    //         if (!$label) {
    //             continue; // Lanjut ke prediksi berikutnya jika tidak ada label
    //         }

    //         // Ambil info detail dari database berdasarkan label (class_name)
    //         $wasteInfo = WasteType::with('materials')
    //             ->where(DB::raw('LOWER(type_name)'), strtolower($label))
    //             ->first();

    //         $handlingTips = [];
    //         $recyclingTips = [];
    //         $description = 'Deskripsi untuk sampah ini belum tersedia.';
    //         $type = 'Tidak Diketahui';

    //         if ($wasteInfo) {
    //             // Asumsi 'type' ada di tabel waste_types (misal: organik, anorganik, b3)
    //             $type = $wasteInfo->type ?? 'Tidak Diketahui';

    //             if ($wasteInfo->materials->isNotEmpty()) {
    //                 $recyclingTips = $wasteInfo->materials->pluck('recycle_info')->filter()->values()->all();
    //                 $handlingTips = $wasteInfo->materials->pluck('suggest')->filter()->unique()->values()->all();
                    
    //                 $descriptions = $wasteInfo->materials->pluck('description_mat')->filter()->values();
    //                 if ($descriptions->isNotEmpty()) {
    //                     $description = $descriptions->random();
    //                 }
    //             }
                
    //             // Coba selesaikan quest jika ada
    //             UserQuestController::tryCompleteScanQuest($wasteInfo->id);
    //         }

    //         // Susun data untuk satu prediksi sesuai format yang dibutuhkan frontend
    //         $processedPredictions[] = [
    //             'label'         => ucfirst(str_replace('_', ' ', $label)), // Ubah 'botol_plastik' menjadi 'Botol plastik'
    //             'type'          => ucfirst($type),
    //             'confidence'    => $confidence,
    //             'description'   => $description,
    //             'handlingTips'  => $handlingTips,
    //             'recyclingTips' => $recyclingTips,
    //             'imageUrl'      => asset('storage/' . $storedPath), // URL gambar yang diunggah
    //         ];
    //     }

    //     // --- 4. Kirim Respons Akhir ke Frontend ---
    //     // Formatnya sekarang sudah sesuai dengan yang diharapkan JavaScript
    //     return response()->json([
    //         'success' => true,
    //         'predictions' => $processedPredictions
    //     ]);
    // }

    public function scan(Request $request)
    {
        // 1. Validasi file yang diunggah dari pengguna
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Wajib, harus gambar, tipe tertentu, maks 5MB
        ]);

        // Simpan file agar mendapatkan URL publik yang akan ditampilkan di hasil
        $storedPath = $request->file('file')->store('uploads', 'public');

        try {
            // 2. Kirim file ke Server AI (Flask) untuk diproses
            $response = Http::timeout(60) // Timeout 60 detik
                ->attach(
                    'file',
                    file_get_contents($request->file('file')->getRealPath()),
                    $request->file('file')->getClientOriginalName()
                )
                ->post(env('FLASK_URL') . '/predict'); // Pastikan FLASK_URL ada di file .env Anda

            // Tangani jika request ke Flask gagal (misal: server Flask mati atau error 500)
            if ($response->failed()) {
                return response()->json(['success' => false, 'message' => 'Server AI gagal memproses gambar.'], 502); // 502 Bad Gateway
            }
            
            $flaskData = $response->json();

        } catch (ConnectionException $e) {
            // Tangani jika Laravel tidak bisa terhubung sama sekali ke server Flask
            report($e);
            return response()->json(['success' => false, 'message' => 'Tidak dapat terhubung ke server AI.'], 504); // 504 Gateway Timeout
        }

        // 3. Proses respons yang diterima dari Flask
        if (!isset($flaskData['predictions']) || !is_array($flaskData['predictions'])) {
            return response()->json(['success' => false, 'message' => 'Format respons dari server AI tidak valid.'], 422);
        }

        // Ambil URL gambar hasil dari root respons Flask
        $resultImageUrl = $flaskData['result_image_url'] ?? asset('storage/' . $storedPath);
        
        $processedPredictions = [];

        // Loop melalui setiap objek dalam array 'predictions'
        foreach ($flaskData['predictions'] as $prediction) {
            $label = $prediction['class_name'] ?? null;
            if (!$label) {
                continue; // Lewati prediksi ini jika tidak ada nama kelas/label
            }

            // Ambil informasi detail dari database berdasarkan label
            $wasteInfo = WasteType::with('materials')
                ->where(DB::raw('LOWER(type_name)'), strtolower($label))
                ->first();

            // Siapkan variabel default
            $handlingTips = [];
            $recyclingTips = [];
            $description = 'Deskripsi untuk sampah ini belum tersedia.';
            $type = 'Tidak Diketahui';

            if ($wasteInfo) {
                $type = $wasteInfo->type ?? 'Tidak Diketahui';
                
                if ($wasteInfo->materials->isNotEmpty()) {
                    $recyclingTips = $wasteInfo->materials->pluck('recycle_info')->filter()->values()->all();
                    $handlingTips = $wasteInfo->materials->pluck('suggest')->filter()->unique()->values()->all();
                    
                    $descriptions = $wasteInfo->materials->pluck('description_mat')->filter()->values();
                    if ($descriptions->isNotEmpty()) {
                        $description = $descriptions->random();
                    }
                }
                
                // Coba selesaikan quest scan (opsional, sesuai logika Anda)
                UserQuestController::tryCompleteScanQuest($wasteInfo->id);
            }

            // Susun data untuk satu prediksi sesuai format yang dibutuhkan frontend
            $processedPredictions[] = [
                'label'         => ucfirst(str_replace('_', ' ', $label)),
                'type'          => ucfirst($type),
                'confidence'    => $prediction['confidence'] ?? 0,
                'description'   => $description,
                'handlingTips'  => $handlingTips,
                'recyclingTips' => $recyclingTips,
                'box_coordinates' => $prediction['box_coordinates'] ?? [], // Simpan juga koordinatnya
            ];
        }

        // 4. Kirim respons akhir yang sudah lengkap ke frontend
        return response()->json([
            'success' => true,
            'result_image_url' => $resultImageUrl, // Kirim URL gambar yang sudah ditandai
            'predictions' => $processedPredictions
        ]);
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
