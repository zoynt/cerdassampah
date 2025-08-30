{{-- resources/views/game.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Game Pilah Sampah')

@push('head')
    {{-- PERBAIKAN: Menambahkan meta CSRF token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- PERBAIKAN: Menambahkan Google Fonts untuk tampilan yang lebih baik --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div id="pilah-sampah" style="width: 100%; height: 100%;"></div>
@endsection

@push('scripts')
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
@endpush
