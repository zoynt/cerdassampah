@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Form Edit Produk</h1>

        @php
            // Menyiapkan semua data awal yang dibutuhkan oleh Alpine.js
            $alpineData = [
                'categoryName' => old('kategori', optional($produk->category)->name ?? ''),
                'categoryList' => $kategoriList,
                'existingImages' => $produk->images->map(fn($img) => ['url' => asset('storage/' . $img->image_path)])
            ];
        @endphp

        <form action="{{ route('marketplace.products.update', $produk->id) }}" method="POST" enctype="multipart/form-data" x-data="formManager({{ Js::from($alpineData) }})">
            @method('PUT')
            @include('pages.marketplace._form')
        </form>
    </div>
@endsection

