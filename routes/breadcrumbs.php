<?php

// routes/breadcrumbs.php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// =================================================================
// HOME
// =================================================================
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// =================================================================
// PROFIL PENGGUNA
// =================================================================
Breadcrumbs::for('profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Edit Profil', route('profile.edit'));
});

// =================================================================
// BANK SAMPAH DIGITAL
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

// Home > Harga Sampah
Breadcrumbs::for('digital.harga', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Cek Harga Sampah', route('digital.harga'));
});

// Home > Informasi Akun > Tarik Saldo
Breadcrumbs::for('digital.tarik-saldo.form', function (BreadcrumbTrail $trail) {
    $trail->parent('digital.informasi');
    $trail->push('Tarik Saldo', route('digital.tarik-saldo.form'));
});

// =================================================================
// MARKETPLACE (UNTUK PENJUAL)
// =================================================================
// Home > Data Penjualan
Breadcrumbs::for('marketplace.penjualan', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Data Penjualan', route('marketplace.penjualan'));
});

// Home > Data Penjualan > Daftar Produk
Breadcrumbs::for('marketplace.produk', function (BreadcrumbTrail $trail) {
    $trail->parent('marketplace.penjualan');
    $trail->push('Daftar Produk', route('marketplace.produk'));
});

// Home > Data Penjualan > Daftar Produk > Tambah Produk
Breadcrumbs::for('products.create', function (BreadcrumbTrail $trail) {
    $trail->parent('marketplace.produk');
    $trail->push('Tambah Produk', route('products.create'));
});

// Home > Data Penjualan > Daftar Produk > Edit Produk
Breadcrumbs::for('products.edit', function (BreadcrumbTrail $trail, $product) {
    $trail->parent('marketplace.produk');
    $trail->push('Edit: ' . $product->nama, route('products.edit', $product->id));
});

// =================================================================
// PROFIL MARKETPLACE (TOKO)
// =================================================================
// Home > Profil Toko
Breadcrumbs::for('marketplace.profile.show', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Profil Toko', route('marketplace.profile.show'));
});

// Home > Profil Toko > Edit Profil Toko
Breadcrumbs::for('marketplace.profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('marketplace.profile.show');
    $trail->push('Edit Profil Toko', route('marketplace.profile.edit'));
});

// =================================================================
// LAPORAN
// =================================================================
// Home > Lapor
Breadcrumbs::for('lapor.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Lapor Sampah', route('lapor.index'));
});

// Home > Histori Laporan
Breadcrumbs::for('laporan.history', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Histori Laporan', route('laporan.history'));
});
