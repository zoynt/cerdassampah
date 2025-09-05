<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\LaporController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserpointController;
use App\Http\Controllers\ReverseGeocodeController;
use App\Http\Controllers\TpsController;
use App\Http\Controllers\SurungController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\QuestController;

// Landing Page
// Route::get('/', function () {
//     return view('pages.landing.index');
// });
Route::get('/', [LandingController::class, 'peta'])->name('landing.peta');
// Informasi
Route::get('/informasi', function () {
    return view('pages.informasi');
})->name('informasi');
// Tentang
Route::get('/tentang', function () {
    return view('pages.tentang');
})->name('tentang');
Route::get('/reverse-geocode', ReverseGeocodeController::class);

// Fitur Scan
Route::get('/scan', function () {
    return view('pages.scan.scan');
})->name('scan.form');
Route::post('/scan', [ScanController::class, 'scan'])->name('scan.scan');

// Tambahkan route ini
Route::get('/game', function () {
    return view('game');
})->name('game-pilah-sampah');


// Auth
// Route::middleware(['auth'])->group(function () {
Route::middleware(['auth', 'role:admin|warga'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard.dashboard');
    })->name('dashboard');
    Route::get('/scan-user', function () {
        return view('pages.dashboard.scan-sampah');
    })->name('scan-user');
    Route::get('/tps', [TpsController::class, 'index'])->name('tps.index');
    Route::get('/surung-sintak', [SurungController::class, 'index'])->name('surung-sintak.index');
    Route::get('/banksampah-user', [BankController::class, 'index'])->name('banksampah-user');
    Route::get('/lokasi-tps', [TpsController::class, 'mapIndex'])->name('lokasi-tps.index');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/dashboard', [QuestController::class, 'index'])->name('dashboard');

    // Route Fitur Lapor
    Route::get('/lapor', function () {
        return view('pages.report.lapor');
    })->name('lapor');
    Route::get('/lapor', [ReportController::class, 'index'])->name('lapor.index');
    Route::post('/lapor', [ReportController::class, 'store'])->name('lapor.store.user');
    Route::get('/histori-laporan', [ReportController::class, 'history'])->name('laporan.history');
});

    Route::get('/leaderboard', [UserpointController::class, 'index'])->name('userpoint.index');
    Route::get('/tps', [TpsController::class, 'index'])->name('tps.index');

// Admin
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/', function () {
//         return view('admin');
//         });
// });
   

require __DIR__.'/auth.php';
