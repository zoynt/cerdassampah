<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TpsController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\QuestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurungController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\UserpointController;
use App\Http\Controllers\UserpointController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ReverseGeocodeController;
use App\Http\Controllers\BankSampahController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MarketplaceProfileController;
use App\Http\Controllers\BankSampahUserController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\BankWasteProductController;



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

// Fitur Scan (publik form + proses)
Route::get('/scan', fn () => view('pages.scan.scan'))->name('scan.form');
Route::post('/scan', [ScanController::class, 'scan'])->name('scan.scan');

// ====== Auth ======
// Area login (role admin|warga)
Route::middleware(['auth', 'role:admin|warga'])->group(function () {
    // Dashboard & menu
    Route::get('/dashboard', fn () => view('pages.dashboard.dashboard'))->name('dashboard');
    Route::get('/scan-user', fn () => view('pages.dashboard.scan-sampah'))->name('scan-user');

    // TPS, Surung, Bank Sampah, Peta
    Route::get('/tps', [TpsController::class, 'index'])->name('tps.index');
    Route::get('/surung-sintak', [SurungController::class, 'index'])->name('surung-sintak.index');
    Route::get('/banksampah-user', [BankController::class, 'index'])->name('banksampah-user');
    Route::get('/lokasi-tps', [TpsController::class, 'mapIndex'])->name('lokasi-tps.index');

    // --- ROUTE BARU DITAMBAHKAN DI SINI ---
    // Bank Sampah Digital
    Route::get('/bank-sampah/informasi', [BankSampahUserController::class, 'informasi'])->name('digital.informasi');
    Route::get('/bank-sampah/riwayat', [BankTransactionController::class, 'riwayat'])->middleware('auth')->name('digital.riwayat');
    Route::get('/bank-sampah/harga', [BankWasteProductController::class, 'harga'])->middleware('auth')->name('digital.harga');
    Route::get('/bank-sampah/tarik-saldo', [BankController::class, 'showTarikSaldoForm'])->name('digital.tarik-saldo.form');
    Route::post('/bank-sampah/tarik-saldo', [BankController::class, 'storeTarikSaldo'])->name('digital.tarik-saldo.store');
    Route::get('/bank-sampah/{slug}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // Route::get('/bank-sampah/{bankSampah}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // Route::get('/bank-sampah/{slug}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // --- AKHIR PENAMBAHAN ROUTE ---

    Route::get('/marketplace/penjualan', [MarketplaceController::class, 'index'])->name('marketplace.penjualan');
    Route::get('/marketplace/produk', [ProductController::class, 'index'])->name('marketplace.produk');
    Route::resource('products', ProductController::class);
    Route::get('/marketplace/riwayat', [ProductController::class, 'riwayatPenjualan'])->name('marketplace.riwayat');
    Route::get('/marketplace/penjualan/{penjualan}', [ProductController::class, 'showPenjualan'])->name('marketplace.penjualan.show');
    Route::get('/marketplace/profile', [ProductController::class, 'createProfile'])->name('marketplace.profile.create');
    Route::post('/marketplace/profile', [ProductController::class, 'storeProfile'])->name('marketplace.profile.store');
    // Route::get('/marketplace/profile', [MarketplaceProfileController::class, 'edit'])->name('marketplace.profile.edit');
    // Route::post('/marketplace/profile', [MarketplaceProfileController::class, 'update'])->name('marketplace.profile.update');

    Route::get('/marketplace/profile', [MarketplaceProfileController::class, 'show'])->name('marketplace.profile.show');
    Route::get('/marketplace/profile/edit', [MarketplaceProfileController::class, 'edit'])->name('marketplace.profile.edit');
    Route::post('/marketplace/profile', [MarketplaceProfileController::class, 'update'])->name('marketplace.profile.update');


    // Profil
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

    // Game (publik)
    Route::get('/game', [LeaderboardController::class, 'index'])
        ->name('game-pilah-sampah');

    Route::post('/api/game/points', [UserpointController::class, 'store'])
        ->name('game.points.store');

        // Leaderboard halaman web (bukan API)
        Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
        Route::post('/leaderboard/fetch', [LeaderboardController::class, 'fetch'])->name('leaderboard.fetch');
        Route::get('/marketplace/product', function () {
            return view('pages.marketplace.product');
        })->name('marketplace.product');
        Route::get('/marketplace/product/{id}', function ($id) {
            return view('pages.marketplace.detail');
        })->name('marketplace.product.detail');
        Route::get('/marketplace/checkout', function () {
            return view('pages.marketplace.checkout');
        })->name('marketplace.checkout');
        Route::get('/marketplace/pembelian/detail', function () {
            return view('pages.marketplace.purchase-detail');
        })->name('marketplace.purchase.detail');
        Route::get('/marketplace/history', function () {
            return view('pages.marketplace.history');
        })->name('marketplace.history');
        Route::get('/marketplace/invoice', function () {
            return view('pages.marketplace.invoice');
        })->name('marketplace.invoice');
        Route::get('/marketplace/store', function () {
            return view('pages.marketplace.store');
        })->name('marketplace.store');
});



require __DIR__.'/auth.php';
