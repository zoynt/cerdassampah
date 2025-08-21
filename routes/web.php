<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\LaporController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReverseGeocodeController;

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

// Route Fitur Lapor
Route::get('/lapor', [LaporController::class, 'index'])->name('lapor');
Route::post('/lapor', [LaporController::class, 'store'])->name('lapor.store');


// Auth
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::get('/admin', function () {
    return view('admin');
});

// Route::get('/pemetaan-tps', function () {
    //     return view('landing/lokasi-tps');
    // });
    
require __DIR__.'/auth.php';