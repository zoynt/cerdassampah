<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dasbor') - CerdasSampah</title>

    {{-- CSS global layout --}}
    @vite('resources/css/app.css')

    {{-- Fonts & Alpine --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>

    @stack('head')
    @stack('styles')
</head>

<body class="text-gray-800">
    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="flex h-screen bg-slate-50">

        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 px-4 py-7 overflow-y-auto text-gray-700 bg-white border-r border-gray-200 transition-transform duration-300 transform"
            :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'md:relative md:translate-x-0': sidebarOpen
            }">

            <a href="{{ route('dashboard') }}" class="flex items-center px-4 mb-8">
                <img src="{{ asset('img/logo.png') }}" alt="Logo CerdasSampah" class="w-10 h-10 mr-3" />
                <span class="text-lg font-bold text-gray-800">CerdasSampah</span>
            </a>

            <nav x-data="{
                    reportOpen: @json(request()->routeIs(['lapor.index', 'laporan.history'])),
                    ruteOpen: @json(request()->routeIs(['tps.index', 'surung-sintak.index', 'banksampah-user'])),
                    digitalOpen: @json(request()->routeIs(['digital.informasi', 'digital.harga', 'digital.riwayat']))
                 }">
                <a href="{{ route('dashboard') }}" @class(['flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200', 'bg-green-700 text-white shadow-sm' => request()->routeIs('dashboard'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('dashboard')])>
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Home
                </a>

                <div class="mt-2">
                    <button @click="reportOpen = !reportOpen" @class(['flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-left rounded-lg transition-colors duration-200', 'bg-green-700 text-white shadow-sm' => request()->routeIs(['lapor.index', 'laporan.history']), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs(['lapor.index', 'laporan.history'])])>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Laporan & Histori
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': reportOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="reportOpen" x-transition class="mt-2 ml-4 space-y-2">
                        <a href="{{ route('lapor.index') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('lapor.index'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('lapor.index')])>
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            Laporan TPS Ilegal
                        </a>
                        <a href="{{ route('laporan.history') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('laporan.history'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('laporan.history')])>
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Histori Laporan
                        </a>
                    </div>
                </div>

                <a href="{{ route('game-pilah-sampah') }}" @class(['flex items-center px-4 py-2.5 mt-2 text-sm font-medium rounded-lg transition-colors duration-200', 'bg-green-700 text-white shadow-sm' => request()->routeIs('game-pilah-sampah'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('game-pilah-sampah')])>
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Game Pilah Sampah
                </a>

                <a href="{{ route('scan-user') }}" @class(['flex items-center px-4 py-2.5 mt-2 text-sm font-medium rounded-lg transition-colors duration-200', 'bg-green-700 text-white shadow-sm' => request()->routeIs('scan-user'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('scan-user')])>
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Scan Sampah
                </a>

                <div class="mt-2">
                    <button @click="ruteOpen = !ruteOpen" @class(['flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-left rounded-lg transition-colors duration-200', 'bg-green-700 text-white shadow-sm' => request()->routeIs(['tps.index', 'surung-sintak.index', 'banksampah-user']), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs(['tps.index', 'surung-sintak.index', 'banksampah-user'])])>
                        <span class="flex items-center"><svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>Rute & Jadwal</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': ruteOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="ruteOpen" x-transition class="mt-2 ml-4 space-y-2">
                        <a href="{{ route('tps.index') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('tps.index'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('tps.index')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Lokasi TPS
                        </a>
                        <a href="{{ route('surung-sintak.index') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('surung-sintak.index'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('surung-sintak.index')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            Surung Sintak
                        </a>
                        <a href="{{ route('banksampah-user') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('banksampah-user'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('banksampah-user')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            Bank Sampah
                        </a>
                    </div>
                </div>

                <div class="mt-2">
                    <button @click="digitalOpen = !digitalOpen" @class([
                        'flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-left rounded-lg transition-colors duration-200',
                        'bg-green-700 text-white shadow-sm' => request()->routeIs(['digital.informasi', 'digital.harga', 'digital.riwayat', 'digital.tarik-saldo.form']),
                        'text-gray-500 hover:bg-gray-200' => !request()->routeIs(['digital.informasi', 'digital.harga', 'digital.riwayat', 'digital.tarik-saldo.form']),
                    ])>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            Bank Sampah Digital
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': digitalOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="digitalOpen" x-transition class="mt-2 ml-4 space-y-2">
                        <a href="{{ route('digital.informasi') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('digital.informasi'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('digital.informasi')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Informasi
                        </a>
                        <a href="{{ route('digital.harga') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('digital.harga'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('digital.harga')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M246.15,133.18,146.83,33.86A19.85,19.85,0,0,0,132.69,28H40A12,12,0,0,0,28,40v92.69a19.85,19.85,0,0,0,5.86,14.14l99.32,99.32a20,20,0,0,0,28.28,0l84.69-84.69A20,20,0,0,0,246.15,133.18Zm-98.83,93.17L52,131V52h79l95.32,95.32ZM104,88A16,16,0,1,1,88,72,16,16,0,0,1,104,88Z"></path></svg>
                            Harga Sampah
                        </a>
                        <a href="{{ route('digital.riwayat') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('digital.riwayat'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('digital.riwayat')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Riwayat Transaksi
                        </a>
                    </div>
                </div>

                <div class="mt-2">
                    <button @click="marketOpen = !marketOpen" @class([
                        'flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-left rounded-lg transition-colors duration-200',
                        'bg-green-700 text-white shadow-sm' => request()->routeIs(['digital.informasi', 'digital.harga', 'digital.riwayat', 'digital.tarik-saldo.form']),
                        'text-gray-500 hover:bg-gray-200' => !request()->routeIs(['digital.informasi', 'digital.harga', 'digital.riwayat', 'digital.tarik-saldo.form']),
                    ])>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M236,96a12,12,0,0,0-.44-3.3L221.2,42.51A20.08,20.08,0,0,0,202,28H54A20.08,20.08,0,0,0,34.8,42.51L20.46,92.7A12,12,0,0,0,20,96h0v16a43.94,43.94,0,0,0,16,33.92V216a12,12,0,0,0,12,12H208a12,12,0,0,0,12-12V145.92A43.94,43.94,0,0,0,236,112V96ZM57.05,52H199l9.14,32H47.91Zm91,56v4a20,20,0,0,1-40,0v-4ZM53,128.71A20,20,0,0,1,44,112v-4H84v4a20,20,0,0,1-20,20,19.76,19.76,0,0,1-9.07-2.2A11.54,11.54,0,0,0,53,128.71ZM196,204H60V155.81c1.32.12,2.65.19,4,.19a43.86,43.86,0,0,0,32-13.85,43.89,43.89,0,0,0,64,0A43.86,43.86,0,0,0,192,156c1.35,0,2.68-.07,4-.19Zm16-92a20,20,0,0,1-9,16.71,11.66,11.66,0,0,0-1.88,1.09A20,20,0,0,1,172,112v-4h40Z"></path></svg>
                            Marketplace
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': digitalOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="marketOpen" x-transition class="mt-2 ml-4 space-y-2">
                        <a href="{{ route('digital.informasi') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('digital.informasi'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('digital.informasi')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Produk
                        </a>
                        <a href="{{ route('digital.harga') }}" @class(['flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium transition-colors duration-200 rounded-lg', 'bg-green-100 text-green-800' => request()->routeIs('digital.harga'), 'text-gray-500 hover:bg-gray-200' => !request()->routeIs('digital.harga')])>
                            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Riwayat Transaksi
                        </a>
                    </div>
                </div>

            </nav>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/50 md:hidden" x-cloak></div>

        <div class="flex flex-col flex-1 w-full transition-transform duration-300">
            <header @class([
                'sticky top-0 z-20 flex items-center justify-between px-6 py-4 text-white bg-green-700 transition-shadow duration-300',
                'shadow-md' => !request()->routeIs(
                    'dashboard',
                    'scan-user',
                    'profile.edit'),
            ])>
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <h1 class="ml-3 text-xl font-semibold">@yield('title')</h1>
                </div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                            class="relative z-10 flex items-center p-1 rounded-full focus:outline-none hover:bg-black/10 transition-colors">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white/50">
                            @if (Auth::user()->profile_photo_path)
                                <img class="w-full h-full object-cover"
                                     src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                     alt="Foto Profil">
                            @else
                                <img class="w-full h-full object-cover"
                                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                                     alt="Avatar User">
                            @endif
                        </div>
                        <div class="hidden ml-3 text-left md:block">
                            <p class="font-semibold leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-white/80">Warga</p>
                        </div>
                        <svg class="w-5 h-5 ml-2 hidden md:block" :class="{ 'rotate-180': dropdownOpen }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-30 w-48 py-2 mt-2 origin-top-right text-gray-800 bg-white rounded-md shadow-xl"
                         style="display: none;">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Keluar
                            </a>
                        </form>
                    </div>
                </div>
            </header>

            <main class="relative z-10 flex-1 overflow-y-auto">
                @if (request()->routeIs('dashboard') || request()->routeIs('scan-user') || request()->routeIs('profile.edit') || request()->routeIs('game-pilah-sampah'))
                    @yield('content')
                @else
                    <div class="py-6 px-4 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                @endif
            </main>
        </div>
    </div>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Oke'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn-custom' }
            });
        </script>
    @endif

    @if (request()->routeIs('scan-user'))
        <div x-data="{ showPopup: true, currentStep: 0 }" x-show="showPopup" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" x-cloak>
            <div class="bg-white rounded-xl shadow-lg text-center w-full max-w-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Langkah Mudah Menggunakan Fitur Scan</h2>

                <div class="flex items-center justify-center gap-5 mb-4">
                    <button @click="currentStep = Math.max(0, currentStep - 1)"
                            class="text-3xl text-gray-400 hover:text-green-700">&lt;</button>
                    <div class="flex gap-3">
                        <template x-for="i in 3">
                            <button @click="currentStep = i - 1"
                                    :class="{
                                        'bg-green-700 text-white': currentStep === i - 1,
                                        'bg-gray-200 text-gray-600': currentStep !== i - 1
                                    }"
                                    class="w-10 h-10 rounded-full font-bold text-lg transition-colors"
                                    x-text="i"></button>
                        </template>
                    </div>
                    <button @click="currentStep = Math.min(2, currentStep + 1)"
                            class="text-3xl text-gray-400 hover:text-green-700">&gt;</button>
                </div>

                <div class="text-gray-600">
                    <div x-show="currentStep === 0">
                        <h3 class="mb-2 font-bold text-lg">1. Unggah Gambar Sampah</h3>
                        <p>Ambil foto sampah menggunakan kamera atau unggah gambar dari galeri Anda.</p>
                    </div>
                    <div x-show="currentStep === 1" style="display:none;">
                        <h3 class="mb-2 font-bold text-lg">2. Sistem Menganalisis Gambar</h3>
                        <p>Sistem cerdas kami akan memproses dan mengenali jenis sampah berdasarkan pola visual.</p>
                    </div>
                    <div x-show="currentStep === 2" style="display:none;">
                        <h3 class="mb-2 font-bold text-lg">3. Hasil Analisis Ditampilkan</h3>
                        <p>Hasil klasifikasi (Organik, Anorganik, B3) akan muncul secara otomatis.</p>
                    </div>
                </div>

                <button @click="showPopup = false"
                        class="mt-6 w-full py-2 px-4 bg-green-700 text-white font-semibold rounded-full hover:bg-green-800 transition">
                    Mengerti
                </button>
            </div>
        </div>
    @endif
</body>
</html>
