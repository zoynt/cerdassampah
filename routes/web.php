<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\LaporController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReverseGeocodeController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

// Landing Page
Route::get('/', function () {
    return view('pages.landing.index');
});
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



// Auth
// Route::middleware(['auth'])->group(function () {
Route::middleware(['auth', 'role:admin|warga'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard.dashboard');
    })->name('dashboard');

    Route::get('/lokasi-tps', function () {
        return view('pages.dashboard.lokasi-tps');
    })->name('lokasi-tps.index');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');


    // Route Fitur Lapor
    Route::get('/lapor', function () {
        return view('pages.report.lapor');
    })->name('lapor');
    Route::get('/lapor', [ReportController::class, 'index'])->name('lapor.index');
    Route::post('/lapor', [ReportController::class, 'store'])->name('lapor.store.user');
});


// Admin
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/', function () {
//         return view('admin');
//         });
// });
   

require __DIR__.'/auth.php';
