<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/scan', function () {
//     return view('scan');
// });
Route::get('/scan', function () {
    return view('scan');
})->name('scan.form');

Route::post('/scan', [ScanController::class, 'scan'])->name('scan.process');
