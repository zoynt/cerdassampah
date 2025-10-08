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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MarketplaceProfileController;
use App\Http\Controllers\BankSampahUserController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\BankWasteProductController;
use App\Http\Controllers\CompanyTransactionController;



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
    Route::get('/bank-sampah/informasi/{bank:slug?}', [BankSampahUserController::class, 'informasi'])->name('digital.informasi');
    Route::get('/bank-sampah/riwayat/{bank:slug?}', [BankTransactionController::class, 'riwayat'])->middleware('auth')->name('digital.riwayat');
    Route::get('/bank-sampah/harga/{bank:slug?}', [BankWasteProductController::class, 'harga'])->middleware('auth')->name('digital.harga');
    Route::get('/bank-sampah/tarik-saldo/{bank:slug}', [BankSampahUserController::class, 'showTarikSaldoForm'])->middleware('auth')->name('digital.tarik-saldo.form');
    Route::post('/bank-sampah/tarik-saldo/{bank:slug}', [BankSampahUserController::class, 'storeTarikSaldo'])->middleware('auth')->name('digital.tarik-saldo.store');
    // Route::get('/bank-sampah/tarik-saldo', [BankSampahUserController::class, 'showTarikSaldoForm'])->name('digital.tarik-saldo.form');
    // Route::post('/bank-sampah/tarik-saldo', [BankSampahUserController::class, 'storeTarikSaldo'])->name('digital.tarik-saldo.store');
    Route::get('/bank-sampah/{bank:slug}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // Route::get('/bank-sampah/riwayat', [BankTransactionController::class, 'riwayat'])->middleware('auth')->name('digital.riwayat');
    // Route::get('/bank-sampah/{bank:slug}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // Route::get('/bank-sampah/{slug}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // Route::get('/bank-sampah/{bankSampah}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // Route::get('/bank-sampah/{slug}', [BankController::class, 'show'])->name('digital.banksampah.show');
    // --- AKHIR PENAMBAHAN ROUTE ---

     // Marketplace

    // Route Marketplace Umum (Pembelian)
    Route::get('/marketplace/product', [ProductController::class, 'index'])->name('marketplace.products.all'); 
    Route::get('/marketplace/product/{product}', [ProductController::class, 'show'])->name('marketplace.products.show'); // Detail produk tunggal (diganti dari marketplace.product.detail)
    Route::get('/marketplace/checkout', [ProductController::class, 'showCheckout'])->name('marketplace.checkout');
    Route::get('/marketplace/pembelian/{order}', [OrderController::class, 'showPurchaseDetail'])->name('marketplace.purchase.detail');
    Route::post('/marketplace/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('marketplace.order.cancel');
    Route::post('/marketplace/checkout/{product}', [OrderController::class, 'placeOrder'])->name('marketplace.order.place');
    Route::get('/marketplace/invoice/{order}', [OrderController::class, 'showInvoice'])->name('marketplace.invoice.show');
    Route::get('/marketplace/history', [OrderController::class, 'purchaseHistory'])->name('marketplace.history');
    Route::get('/marketplace/rating/{order}', [ProductController::class, 'showRatingForm'])->name('marketplace.rating.show');
    Route::post('/marketplace/rating/{order}', [ProductController::class, 'storeRating'])->name('marketplace.rating.store');


    // Route Marketplace Penjual (Seller/Toko)

    // Daftar Produk Toko Saya (List/Read)
    Route::get('/marketplace/products/list', [ProductController::class, 'storeProducts'])->name('marketplace.products.list');
    
    // CRUD Produk (Create, Store, Edit, Update, Delete)
    Route::get('/marketplace/products/create', [ProductController::class, 'create'])->name('marketplace.products.create'); 
    Route::post('/marketplace/products', [ProductController::class, 'store'])->name('marketplace.products.store'); 
    Route::get('/marketplace/products/{product}/edit', [ProductController::class, 'edit'])->name('marketplace.products.edit'); 
    Route::put('/marketplace/products/{product}', [ProductController::class, 'update'])->name('marketplace.products.update'); 
    // Route::delete('/marketplace/products/{produk}', [ProductController::class, 'destroy'])->name('marketplace.products.destroy'); 

    // Penjualan & Riwayat Toko
    //Route::get('/marketplace/penjualan', [MarketplaceController::class, 'index'])->name('marketplace.penjualan');
    Route::get('/marketplace/riwayat', [ProductController::class, 'riwayatPenjualan'])->name('marketplace.riwayat');
    Route::get('/marketplace/riwayat/export', [ProductController::class, 'exportSalesHistory'])->name('marketplace.riwayat.export');
    Route::post('/marketplace/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('marketplace.order.complete');
    //Route::get('/marketplace/penjualan/{penjualan}', [ProductController::class, 'showPenjualan'])->name('marketplace.penjualan.show');
    
    // Profil Toko (Marketplace Profile)
    Route::get('/store/profile/create', [StoreProfileController::class, 'create'])->name('store.profile.create'); 
    Route::post('/store/profile', [StoreProfileController::class, 'store'])->name('store.profile.store'); 
    Route::get('/store/profile/{store}', [StoreProfileController::class, 'show'])->name('store.profile.show');
    Route::get('/store/profile/{store}/edit', [StoreProfileController::class, 'edit'])->name('store.profile.edit');
    Route::put('/store/profile', [StoreProfileController::class, 'update'])->name('store.profile.update');

    // Halaman Toko (Publik)
    Route::get('marketplace/store/{store}', [StoreController::class, 'show'])
    ->name('marketplace.store.show');
    Route::get('/my-store/dashboard', [StoreProfileController::class, 'redirectToMyStore'])->name('mystore.dashboard');
    


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
});



require __DIR__.'/auth.php';
