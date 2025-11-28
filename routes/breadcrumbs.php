<?php

// routes/breadcrumbs.php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use App\Models\User;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product; // Asumsi Anda punya model Product

// =================================================================
// HOME
// =================================================================
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// =================================================================
// PROFIL PENGGUNA
// =================================================================
// Home > Edit Profil
Breadcrumbs::for('profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Edit Profil', route('profile.edit'));
});

// =================================================================
// LAPORAN
// =================================================================
// Home > Lapor Sampah
Breadcrumbs::for('lapor.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Lapor Sampah', route('lapor.index'));
});

// Home > Histori Laporan
Breadcrumbs::for('laporan.history', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Histori Laporan', route('laporan.history'));
});

// =================================================================
// EDUKASI & GAME
// =================================================================
// Home > Scan Sampah
Breadcrumbs::for('scan-user', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Scan Sampah', route('scan-user'));
});

// Home > Game Pilah Sampah
Breadcrumbs::for('game-pilah-sampah', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Game Pilah Sampah', route('game-pilah-sampah'));
});

// Home > Leaderboard
Breadcrumbs::for('leaderboard.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Leaderboard', route('leaderboard.index'));
});

// =================================================================
// PEMETAAN (TPS, SURUNG SINTAK, BANK SAMPAH)
// =================================================================
// Home > Lokasi TPS
Breadcrumbs::for('tps.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Lokasi TPS', route('tps.index'));
});

// Home > Surung Sintak
Breadcrumbs::for('surung-sintak.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Surung Sintak', route('surung-sintak.index'));
});

// Home > Bank Sampah (Halaman Peta)
Breadcrumbs::for('banksampah-user', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Bank Sampah', route('banksampah-user'));
});

// Home > Bank Sampah > [Nama Bank]
Breadcrumbs::for('digital.banksampah.show', function (BreadcrumbTrail $trail, Bank $bank) {
    $trail->parent('banksampah-user');
    $trail->push($bank->bank_name, route('digital.banksampah.show', $bank));
});

// Home > Bank Sampah > Profil [Nama Bank]
Breadcrumbs::for('bank-sampah.profil.show', function (BreadcrumbTrail $trail, Bank $bank) {
    $trail->parent('banksampah-user');
    $trail->push('Profil: ' . $bank->bank_name, route('bank-sampah.profil.show', $bank));
});

// Home > Bank Sampah > Item [Nama Bank]
Breadcrumbs::for('bank-sampah.item.index', function (BreadcrumbTrail $trail, Bank $bank) {
    $trail->parent('banksampah-user');
    $trail->push('Item: ' . $bank->bank_name, route('bank-sampah.item.index', $bank));
});

// =================================================================
// BANK SAMPAH DIGITAL (NASABAH)
// =================================================================
// Home > Informasi Akun
Breadcrumbs::for('digital.informasi', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Informasi Akun', route('digital.informasi'));
});

// Home > Informasi Akun > Riwayat Transaksi
Breadcrumbs::for('digital.riwayat', function (BreadcrumbTrail $trail) {
    $trail->parent('digital.informasi');
    $trail->push('Riwayat Transaksi', route('digital.riwayat'));
});

// Home > Informasi Akun > Cek Harga Sampah
Breadcrumbs::for('digital.harga', function (BreadcrumbTrail $trail, Bank $bank) {
    $trail->parent('digital.informasi');
    $trail->push('Cek Harga: ' . $bank->bank_name, route('digital.harga', $bank));
});

// Home > Informasi Akun > Tarik Saldo
Breadcrumbs::for('digital.tarik-saldo.form', function (BreadcrumbTrail $trail, Bank $bank) {
    $trail->parent('digital.informasi');
    $trail->push('Tarik Saldo', route('digital.tarik-saldo.form', $bank));
});

// =================================================================
// PENGELOLA BANK SAMPAH (BANKER)
// =================================================================
// Home > Kelola Bank Sampah
Breadcrumbs::for('pengelola.bank-profil.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Kelola Bank Sampah', route('pengelola.bank-profil.edit'));
});

// Home > Data Nasabah
Breadcrumbs::for('pengelola.nasabah.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Data Nasabah', route('pengelola.nasabah.index'));
});

// Home > Data Nasabah > [Nama Nasabah]
Breadcrumbs::for('pengelola.nasabah.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('pengelola.nasabah.index');
    $trail->push($user->name, route('pengelola.nasabah.show', $user));
});

// Home > Harga Sampah
Breadcrumbs::for('pengelola.harga.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Harga Sampah', route('pengelola.harga.index'));
});

// Home > Riwayat Setoran
Breadcrumbs::for('pengelola.riwayat.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Riwayat Setoran', route('pengelola.riwayat.index'));
});

// Home > Riwayat Setoran > Setoran Baru
Breadcrumbs::for('pengelola.setoran.create', function (BreadcrumbTrail $trail) {
    $trail->parent('pengelola.riwayat.index');
    $trail->push('Setoran Baru', route('pengelola.setoran.create'));
});

// Home > Riwayat Setoran > Detail Transaksi
Breadcrumbs::for('pengelola.riwayat.show', function (BreadcrumbTrail $trail, BankTransaction $transaction) {
    $trail->parent('pengelola.riwayat.index');
    $trail->push('Detail: ' . $transaction->uuid, route('pengelola.riwayat.show', $transaction));
});

// Home > Riwayat Setoran > Detail Transaksi > Cetak Struk
Breadcrumbs::for('pengelola.riwayat.cetak', function (BreadcrumbTrail $trail, BankTransaction $transaction) {
    $trail->parent('pengelola.riwayat.show', $transaction);
    $trail->push('Cetak Struk', route('pengelola.riwayat.cetak', $transaction));
});

// Home > Riwayat Pembayaran
Breadcrumbs::for('pengelola.pembayaran.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Riwayat Pembayaran', route('pengelola.pembayaran.index'));
});

// Home > Riwayat Pembayaran > Pembayaran Baru
Breadcrumbs::for('pengelola.pembayaran.create', function (BreadcrumbTrail $trail) {
    $trail->parent('pengelola.pembayaran.index');
    $trail->push('Pembayaran Baru', route('pengelola.pembayaran.create'));
});

// Home > Riwayat Pembayaran > Detail Pembayaran
Breadcrumbs::for('pengelola.pembayaran.show', function (BreadcrumbTrail $trail, BankTransaction $payment) {
    $trail->parent('pengelola.pembayaran.index');
    $trail->push('Detail: ' . $payment->uuid, route('pengelola.pembayaran.show', $payment));
});

// =================================================================
// MARKETPLACE (UMUM)
// =================================================================
// Home > Marketplace
Breadcrumbs::for('marketplace.products.all', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Marketplace', route('marketplace.products.all'));
});

// Home > Marketplace > [Nama Toko]
// Breadcrumbs::for('marketplace.store.show', function (BreadcrumbTrail $trail, Store $store) {
//     $trail->parent('marketplace.products.all');
//     $trail->push($store->name, route('marketplace.store.show', $store));
// });

// Home > Marketplace > [Nama Toko] > [Nama Produk]
// Asumsi $product_slug adalah ID atau slug dari model Product
Breadcrumbs::for('marketplace.products.show', function (BreadcrumbTrail $trail, Store $store, $product_slug) {
    // Anda mungkin perlu mengambil data produk di sini jika $product_slug bukan objek
    // Untuk contoh ini, kita anggap $product_slug adalah string nama
    $trail->parent('marketplace.products.all', $store);
    $trail->push($product_slug, route('marketplace.products.show', [$store, $product_slug]));
});

// Home > Marketplace > [Nama Toko] > [Nama Produk] > Checkout
Breadcrumbs::for('marketplace.checkout', function (BreadcrumbTrail $trail, Store $store, $product_slug) {
    $trail->parent('marketplace.products.show', $store, $product_slug);
    $trail->push('Checkout', route('marketplace.checkout', [$store, $product_slug]));
});

// Home > Riwayat Transaksi
Breadcrumbs::for('marketplace.history', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Riwayat Transaksi', route('marketplace.history'));
});

// Home > Riwayat Transaksi > [Nomor Order]
Breadcrumbs::for('marketplace.purchase.detail', function (BreadcrumbTrail $trail, Order $order) {
    $trail->parent('marketplace.history');
    $trail->push('Detail Pembelian #' . $order->order_number, route('marketplace.purchase.detail', $order));
});

// Home > Riwayat Transaksi > [Nomor Order] > Invoice
Breadcrumbs::for('marketplace.invoice.show', function (BreadcrumbTrail $trail, Order $order) {
    $trail->parent('marketplace.purchase.detail', $order);
    $trail->push('Invoice', route('marketplace.invoice.show', $order));
});

// Home > Riwayat Transaksi > [Nomor Order] > Beri Rating
Breadcrumbs::for('marketplace.rating.show', function (BreadcrumbTrail $trail, Order $order) {
    $trail->parent('marketplace.purchase.detail', $order);
    $trail->push('Beri Rating', route('marketplace.rating.show', $order));
});

// =================================================================
// PROFIL TOKO (PENJUAL)
// =================================================================
// Home > Dasbor Toko
Breadcrumbs::for('mystore.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Dasbor Toko', route('mystore.dashboard'));
});

// Home > Buat Toko
Breadcrumbs::for('store.profile.create', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Buat Toko', route('store.profile.create'));
});

// Home > Profil Toko
Breadcrumbs::for('store.profile.show', function (BreadcrumbTrail $trail, Store $store) {
    $trail->parent('dashboard');
    $trail->push('Profil Toko', route('store.profile.show', $store));
});

// Home > Profil Toko > Edit Profil Toko
Breadcrumbs::for('store.profile.edit', function (BreadcrumbTrail $trail, Store $store) {
    $trail->parent('store.profile.show', $store);
    $trail->push('Edit Profil Toko', route('store.profile.edit', $store));
});

// Home > Daftar Produk
Breadcrumbs::for('marketplace.products.list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Daftar Produk', route('marketplace.products.list'));
});

// Home > Daftar Produk > Tambah Produk
Breadcrumbs::for('marketplace.products.create', function (BreadcrumbTrail $trail) {
    $trail->parent('marketplace.products.list');
    $trail->push('Tambah Produk', route('marketplace.products.create'));
});

// Home > Daftar Produk > Edit [Nama Produk]
Breadcrumbs::for('marketplace.products.edit', function (BreadcrumbTrail $trail, $product_slug) {
    // Asumsi $product_slug adalah slug. Anda mungkin perlu mengambil produk.
    $product = Product::where('slug', $product_slug)->firstOrFail(); // Contoh
    $trail->parent('marketplace.products.list');
    $trail->push('Edit: ' . $product->name, route('marketplace.products.edit', $product_slug));
});

// Home > Data Penjualan
Breadcrumbs::for('marketplace.riwayat', function (BreadcrumbTrail $trail, Store $store) {
    $trail->parent('dashboard');
    $trail->push('Data Penjualan', route('marketplace.riwayat', $store));
});
// =================================================================
// Transaksi (MARKETPLACE)
// =================================================================
