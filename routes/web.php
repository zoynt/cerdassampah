<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\LaporController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

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

Route::get('/scan', function () {
    return view('scan/scan');
})->name('scan.form');
Route::post('/scan', [ScanController::class, 'scan'])->name('scan.scan');

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




Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [AuthController::class, 'storeUser'])->name('register.store');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [AuthController::class, 'loginUser'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Rute ini akan mengarahkan ke halaman dasbor setelah login berhasil
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/laporan', function () {
    return view('laporan');
    })->name('laporan.index');
    Route::get('/lokasi-tps', function () {
        return view('lokasi-tps');
    })->name('lokasi-tps.index');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::post('/laporan', [ReportController::class, 'store'])->name('laporan.store.user');
  
});

