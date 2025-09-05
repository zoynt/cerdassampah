@extends('layouts.dashboard')

@section('title', 'Home')

@section('content')

    {{-- Hero Section dengan Latar Bergelombang --}}
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
        <div class="flex items-center justify-center w-8 h-8 mb-4 rounded-full overflow-hidden">
        </div>

        <div class="absolute bottom-[-1px] left-0 w-full text-slate-50">
            <svg viewBox="0 0 1440 120" fill="currentColor" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M1440,120H0V20.48c0,0,202.4,69.52,480,69.52s480-139.04,960-69.52V120Z"></path>
            </svg>
        </div>
    </div>

    {{-- KONTEN KARTU --}}
    <div class="p-4 sm:p-6 lg:p-8 -mt-16 relative z-10 ">
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
            {{-- Kartu Misi dengan Ikon Award Baru --}}
            <div class="flex items-center justify-between p-6 bg-amber-400 rounded-2xl shadow-lg">
                <div>
                    <p class="font-semibold text-amber-900 text-lg">Progres Misi Harian</p>
                    <p class="text-sm text-amber-800">{{ $completedQuests }} dari {{ $totalQuests }} misi selesai</p>
                </div>

                <div class="flex items-center justify-center">
                    <svg class="w-12 h-12 text-white opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center justify-between p-6 bg-amber-400 rounded-2xl shadow-lg">
                <div>
                    <p class="text-xl font-semibold text-amber-900">{{ number_format($userPoints, 0, ',', '.') }} points</p>
                    <p class="text-sm text-amber-800">Ayo terus kumpulkan poinmu!</p>
                </div>
                <div class="flex items-center justify-center">
                    <svg class="w-12 h-12 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 1.27l2.82 5.72 6.32.92-4.57 4.45 1.08 6.3L10 15.5l-5.65 2.97 1.08-6.3-4.57-4.45 6.32-.92z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Daftar Misi Harian --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($dailyQuests as $userQuest)
                {{-- Kartu Misi dengan gaya dinamis berdasarkan status 'is_completed' --}}
                @php
                    $isCompleted = $userQuest->is_completed;
                    $cardClasses = $isCompleted ? 'bg-white' : 'bg-green-700 text-white';
                    $iconWrapperClasses = $isCompleted ? 'bg-gray-100' : 'bg-white/20';
                    $iconClasses = $isCompleted ? 'text-gray-400' : 'text-white';
                    $titleClasses = $isCompleted ? 'text-gray-500' : 'text-white';
                @endphp

                <div
                    class="p-6 text-center rounded-2xl shadow-lg flex flex-col justify-between transition-all duration-300 {{ $cardClasses }}">
                    <div>
                        <div class="inline-block p-5 mb-4 rounded-full {{ $iconWrapperClasses }}">
                            {{-- Perbaikan Logika Ikon --}}
                            @if ($userQuest->quest->quest_type == 'scan')
                                <svg class="w-10 h-10 {{ $iconClasses }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            @else
                                {{-- Asumsikan selain scan adalah game --}}
                                <svg class="w-10 h-10 {{ $iconClasses }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.79 4 4s-1.79 4-4 4c-1.742 0-3.223-.835-3.772-2M12 5v.01M12 19v.01M4.2 10.225l-.01.01M19.8 15.775l-.01.01M4.2 15.775l.01.01M19.8 10.225l.01.01">
                                    </path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-semibold {{ $titleClasses }}">{{ $userQuest->quest->quest_name }}</h3>
                    </div>

                    <div class="mt-4">
                        @if ($isCompleted)
                            <button
                                class="w-full py-2.5 font-semibold text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed"
                                disabled>
                                Selesai
                            </button>
                        @else
                            {{-- --- PERBAIKAN UTAMA DI SINI --- --}}
                            @php
                                $route = '#'; // Fallback default
                                if ($userQuest->quest->quest_type == 'scan') {
                                    $route = route('scan-user');
                                } elseif ($userQuest->quest->quest_type == 'game') {
                                    $route = route('game-pilah-sampah');
                                }
                            @endphp
                            {{-- --- AKHIR PERBAIKAN --- --}}
                            <a href="{{ $route }}"
                                class="block w-full py-2.5 font-semibold text-green-800 bg-green-100 hover:bg-green-200 transition rounded-lg">
                                Kerjakan
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500 col-span-full bg-white rounded-2xl shadow-lg">
                    <p>Tidak ada misi untuk hari ini. Coba kembali besok!</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
