@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Form Edit Produk</h1>

    <form action="{{ route('products.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('pages.marketplace._form')
    </form>
</div>
@endsection
