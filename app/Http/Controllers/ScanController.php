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
}
