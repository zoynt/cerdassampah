@extends('layouts.dashboard')

@section('title', 'Game Pilah Sampah')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @inertiaHead
@endpush

@section('content')
    @inertia
@endsection

@push('scripts')
    {{-- kalau ada tambahan script khusus halaman Inertia --}}
@endpush
