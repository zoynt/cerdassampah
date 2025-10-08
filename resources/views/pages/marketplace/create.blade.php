@extends('layouts.dashboard')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Form Tambah Produk</h1>

        @php
            // Menyiapkan semua data awal yang dibutuhkan oleh Alpine.js
            $alpineData = [
                'categoryName' => old('kategori', optional($produk->category)->name ?? ''),
                'categoryList' => $kategoriList,
                'existingImages' => $produk->images->map(fn($img) => ['url' => asset('storage/' . $img->image_path)])
            ];
        @endphp

        <form action="{{ route('marketplace.products.store') }}" method="POST" enctype="multipart/form-data" x-data="formManager({{ Js::from($alpineData) }})">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert">
                    <p class="font-bold">Oops! Terjadi kesalahan:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('pages.marketplace._form')
        </form>
    </div>
@endsection

