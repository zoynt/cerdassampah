<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\LaporController;
use App\Http\Controllers\ReverseGeocodeController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Informasi
Route::get('/informasi', function () {
    return view('landing/informasi');
});

// Tentang
Route::get('/tentang', function () {
    return view('landing/tentang');
});

Route::get('/admin', function () {
    return view('admin');
});

// Route::get('/pemetaan-tps', function () {
//     return view('landing/lokasi-tps');
// });

// Fitur Scan 
Route::get('/scan', function () {
    return view('scan/scan');
})->name('scan.form');
Route::post('/scan', [ScanController::class, 'scan'])->name('scan.scan');

// Route Fitur Lapor
Route::get('/lapor', function () {
    return view('report/lapor');
});
Route::get('/lapor', [LaporController::class, 'index'])->name('lapor.form');
Route::post('/lapor', [LaporController::class, 'store'])->name('lapor.store');

Route::get('/reverse-geocode', ReverseGeocodeController::class);

