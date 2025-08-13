<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\LaporController;
use App\Http\Controllers\ScanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/informasi', function () {
    return view('landing/informasi');
});

Route::get('/tentang', function () {
    return view('landing/tentang');
});

Route::get('/lapor', function () {
    return view('landing/lapor');
});

Route::get('/admin', function () {
    return view('admin');
});

Route::get('/pemetaan-tps', function () {
    return view('landing/lokasi-tps');
});


Route::get('/scan-sampah', function () {
    return view('landing/scan-sampah');
})->name('scan.form');
Route::post('/scan-sampah', [ScanController::class, 'scan'])->name('scan.scan');

Route::get('/lapor', [LaporController::class, 'index'])->name('lapor.form');
Route::post('/lapor', [LaporController::class, 'store'])->name('lapor.store');



Route::get('/reverse-geocode', function (Illuminate\Http\Request $request) {
    $lat = $request->query('lat');
    $lon = $request->query('lon');

    if (!$lat || !$lon) {
        return response()->json(['error' => 'Missing parameters'], 400);
    }

    $response = Http::withHeaders([
        'User-Agent' => 'YourAppName/1.0'  // ← WAJIB untuk akses Nominatim
    ])->get("https://nominatim.openstreetmap.org/reverse", [
        'lat' => $lat,
        'lon' => $lon,
        'format' => 'json',
    ]);

    if ($response->successful()) {
        return $response->json();
    } else {
        return response()->json([
            'error' => 'Gagal mengambil data dari Nominatim',
            'details' => $response->body(),
            'status' => $response->status(),
        ], $response->status());
    }
});
