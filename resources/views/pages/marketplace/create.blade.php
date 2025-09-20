@extends('layouts.dashboard')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Form Tambah Produk</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @include('pages.marketplace._form')
    </form>
</div>
@endsection
