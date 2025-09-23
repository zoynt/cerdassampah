@extends('layouts.dashboard')

@section('title', 'Tambah Produk Baru')

@section('content')
{{ Breadcrumbs::render() }}
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="bg-green-600 p-6">
        <h1 class="text-2xl font-bold text-white">Form Tambah Produk</h1>
    </div>
    <!-- <h1 class="text-3xl font-bold text-gray-800">Form Tambah Produk</h1> -->

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @include('pages.marketplace._form')
    </form>
    </div>
</div>
@endsection
