@extends('layouts.dashboard')

@section('title', 'Home')

@section('content')

    {{-- Hero Section dengan Latar Bergelombang --}}
    {{-- Margin negatif untuk "menempel" di bawah header sticky --}}
    <div class="relative bg-gradient-to-b from-green-700 via-green-700 to-green-500 -mt-4 sm:-mt-6 text-white mb-6">

        <div class="relative z-10 p-8 pt-10 pb-20 flex flex-col justify-center items-center text-center">
            <div class="flex items-center justify-center w-24 h-24 mb-4 bg-white rounded-full overflow-hidden shadow-md">
                @if (Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-20 h-20 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                @endif
            </div>
            <h2 class="text-4xl font-bold">Halo, {{ Auth::user()->username }}!</h2>
            <p class="mt-1 text-lg text-white/90">Sudah buang sampah hari ini?</p>
        </div>

        {{-- SVG Gelombang dengan warna yang cocok dengan latar belakang konten --}}
        <div class="absolute bottom-[-1px] left-0 w-full text-slate-50">
            <svg viewBox="0 0 1440 120" fill="currentColor" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M1440,120H0V20.48c0,0,202.4,69.52,480,69.52s480-139.04,960-69.52V120Z"></path>
            </svg>
        </div>
    </div>

    {{-- KONTEN KARTU --}}
    {{-- Margin atas negatif agar sedikit menumpuk di atas gelombang --}}
    <div class="p-4 sm:p-6 lg:p-8 -mt-16 relative z-10 ">
        {{-- ======================================================= --}}
        {{-- PENYESUAIAN WARNA PADA BLOK KARTU INI --}}
        {{-- ======================================================= --}}
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
            {{-- Kartu Misi diubah menjadi kuning emas --}}
            <div class="flex items-center justify-between p-6 bg-amber-400 rounded-2xl shadow-lg">
                <div>
                    <p class="font-semibold text-amber-900">Ayo lanjutkan misimu!</p>
                </div>
                <div class="text-3xl font-bold text-amber-900"><span class="text-sm align-top">25/37</span></div>
            </div>
            {{-- Kartu Poin diubah menjadi kuning emas --}}
            <div class="flex items-center justify-start p-6 bg-amber-400 rounded-2xl shadow-lg">
                {{-- Warna bintang diubah menjadi putih agar kontras --}}
                <svg class="w-8 h-8 mr-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 1.27l2.82 5.72 6.32.92-4.57 4.45 1.08 6.3L10 15.5l-5.65 2.97 1.08-6.3-4.57-4.45 6.32-.92z" />
                </svg>
                <span class="text-xl font-semibold text-amber-900">1000 points</span>
            </div>
        </div>
        {{-- ======================================================= --}}
        {{-- AKHIR DARI BLOK PENYESUAIAN --}}
        {{-- ======================================================= --}}


        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="p-6 text-center text-white bg-green-700 rounded-2xl shadow-lg">
                <div class="inline-block p-5 mb-4 bg-white/20 rounded-full">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="font-semibold">Scan 1 sampah daun kering</h3>
                <button
                    class="w-full mt-4 py-2.5 font-semibold text-green-800 bg-green-100 hover:bg-green-200 transition rounded-lg">
                    Kerjakan
                </button>
            </div>

            <div class="p-6 text-center text-white bg-green-700 rounded-2xl shadow-lg">
                <div class="inline-block p-5 mb-4 bg-white/20 rounded-full">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="font-semibold">Scan 2 sampah botol plastik</h3>
                <button
                    class="w-full mt-4 py-2.5 font-semibold text-green-800 bg-green-100 hover:bg-green-200 transition rounded-lg">
                    Kerjakan
                </button>
            </div>

            <div class="p-6 text-center text-white bg-green-700 rounded-2xl shadow-lg">
                <div class="inline-block p-5 mb-4 bg-white/20 rounded-full">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.79 4 4s-1.79 4-4 4c-1.742 0-3.223-.835-3.772-2M12 5v.01M12 19v.01M4.2 10.225l-.01.01M19.8 15.775l-.01.01M4.2 15.775l.01.01M19.8 10.225l.01.01">
                        </path>
                    </svg>
                </div>
                <h3 class="font-semibold">Kerjakan game pilah sampah</h3>
                <button
                    class="w-full mt-4 py-2.5 font-semibold text-green-800 bg-green-100 hover:bg-green-200 transition rounded-lg">
                    Kerjakan
                </button>
            </div>
        </div>
    </div>
@endsection
