<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dasbor') - CerdasSampah</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="text-gray-800">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-slate-50">

        <aside
            class="fixed inset-y-0 left-0 z-30 w-64 px-4 py-7 overflow-y-auto text-gray-700 bg-slate-50 border-r border-gray-200 transition-transform duration-300 transform -translate-x-full md:relative md:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

            <div class="flex items-center justify-between px-4 mb-8">
                <a href="#" class="flex">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo CerdasSampah" class="w-10 h-10 mr-3" />
                    <span class="text-lg font-bold text-gray-700 mt-2">CerdasSampah</span>
                </a>
            </div>

            {{-- NAVIGASI LENGKAP YANG SUDAH DIPERBAIKI --}}
            <nav x-data="{ ruteOpen: false }">
                <a href="{{ route('dashboard') }}" @class([
                    'flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200',
                    'bg-green-700 text-white shadow-sm' => request()->routeIs('dashboard'),
                    'text-gray-500 hover:bg-gray-200' => !request()->routeIs('dashboard'),
                ])>
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Home
                </a>

                <a href="{{ route('lapor.index') }}" @class([
                    'flex items-center px-4 py-2.5 mt-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    'bg-green-700 text-white shadow-sm' => request()->routeIs('lapor.index'),
                    'text-gray-500 hover:bg-gray-200' => !request()->routeIs('lapor.index'),
                ])>
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Laporan
                </a>

                <a href="{{ route('lokasi-tps.index') }}" @class([
                    'flex items-center px-4 py-2.5 mt-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    'bg-green-700 text-white shadow-sm' => request()->routeIs(
                        'lokasi-tps.index'),
                    'text-gray-500 hover:bg-gray-200' => !request()->routeIs(
                        'lokasi-tps.index'),
                ])>
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi TPS
                </a>

                {{-- MENU YANG HILANG DIKEMBALIKAN --}}
                <a href="#" @class([
                    'flex items-center px-4 py-2.5 mt-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    'bg-green-700 text-white shadow-sm' => request()->routeIs('game.*'),
                    'text-gray-500 hover:bg-gray-200' => !request()->routeIs('game.*'),
                ])>
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Game Pilah Sampah
                </a>

                <a href="#" @class([
                    'flex items-center px-4 py-2.5 mt-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    'bg-green-700 text-white shadow-sm' => request()->routeIs('scan.*'),
                    'text-gray-500 hover:bg-gray-200' => !request()->routeIs('scan.*'),
                ])>
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Scan Sampah
                </a>

                <div class="mt-2">
                    <button @click="ruteOpen = !ruteOpen"
                        class="flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-left text-gray-500 transition-colors duration-200 hover:bg-gray-200 rounded-lg">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                </path>
                            </svg>
                            Rute & Jadwal
                        </span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': ruteOpen }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="ruteOpen" x-transition class="mt-2 ml-4 space-y-2">
                        <a href="#"
                            class="flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-500 transition-colors duration-200 hover:bg-gray-200 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            TPS
                        </a>
                        <a href="#"
                            class="flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-500 transition-colors duration-200 hover:bg-gray-200 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-2h8a1 1 0 001-1z"></path>
                            </svg>
                            Surung Sintak
                        </a>
                        <a href="#"
                            class="flex items-center w-full py-2 pl-8 pr-4 text-sm font-medium text-gray-500 transition-colors duration-200 hover:bg-gray-200 rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-2h8a1 1 0 001-1z"></path>
                            </svg>
                            Bank Sampah
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 md:hidden"
            x-cloak></div>

        <div class="flex flex-col flex-1">

            <header
                class="sticky top-0 z-20 flex items-center justify-between px-6 py-4 text-white bg-green-700 shadow-md">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none md:hidden">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <h1 class="ml-3 text-xl font-semibold">@yield('title', 'Dasbor')</h1>
                </div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative z-10 flex items-center p-1 rounded-full focus:outline-none hover:bg-black/10 transition-colors">

                        {{-- PENYESUAIAN: Menampilkan foto profil user jika ada --}}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-0 h-full w-full">
                    </div>
                    <div x-show="dropdownOpen" x-transition
                        class="absolute right-0 z-30 w-48 py-2 mt-2 text-gray-800 bg-white rounded-md shadow-xl">
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

            <main class="relative z-0 flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @yield('content')
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
        {{-- title: 'Laporan Gagal Dikirim', --}}
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: 'btn-custom'  // Kelas khusus untuk tombol
        }
    });
{{-- <div class="alert alert-danger" role="alert">
    {{ session('error') }}
</div> --}}
</script>
@endif
</body>

</html>
