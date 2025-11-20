{{-- 
    PERUBAHAN 1:
    - x-data dipindahkan ke tag <header>
    - Menambahkan state 'activeSection'
    - 'open' (untuk mobile) juga dikelola di sini
--}}
<header id="main-header" 
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-transparent"
    x-data="{ 
        open: false, 
        activeSection: '{{ request()->is('smartcity-informasi') ? 'informasi' : 'beranda' }}' 
    }">

    <div class="mx-auto max-w-7xl px-8 md:px-6">
        <div class="flex items-center justify-between h-20">
            
            <div class="flex-shrink-0">
                <a href="/smartcity" class="flex items-center space-x-3" @click="activeSection = 'beranda'">
                    <span class="text-white font-bold text-xl">SmartCity.id</span>
                </a>
            </div>

            {{-- 
                PERUBAHAN 2: Navigasi Desktop
                - Menghapus {{ request()->is(...) }}
                - Menambahkan @click untuk MENGATUR activeSection
                - Menambahkan :class untuk MEMBACA activeSection
            --}}
            <nav class="hidden lg:flex items-center space-x-2 text-sm font-semibold text-white">
                <a href="/smartcity" 
                   @click="activeSection = 'beranda'"
                   :class="activeSection === 'beranda' ? 'bg-blue-600' : 'hover:bg-white/10'"
                   class="px-4 py-2 rounded-md transition">
                   Beranda
                </a>
                <a href="/smartcity#smart" 
                   @click="activeSection = 'pilar'"
                   :class="activeSection === 'pilar' ? 'bg-blue-600' : 'hover:bg-white/10'"
                   class="px-4 py-2 rounded-md transition">
                   Pilar Kota Cerdas
                </a>
                <a href="/smartcity-informasi" 
                   @click="activeSection = 'informasi'"
                   :class="activeSection === 'informasi' ? 'bg-blue-600' : 'hover:bg-white/10'"
                   class="px-4 py-2 rounded-md transition">
                   Informasi
                </a>
            </nav>

            {{-- 
                PERUBAHAN 3: Navigasi Mobile
                - Menghapus x-data="{ open: false }" dari div ini (karena sudah pindah ke header)
            --}}
            <div @click.away="open = false" class="lg:hidden">
                <div class="lg:hidden absolute top-6 right-4">
                    {{-- Tombol ini sekarang mengontrol 'open' dari header --}}
                    <button @click="open = !open" class="text-white focus:outline-none">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>

                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                     class="absolute top-20 left-0 w-full bg-white rounded-md shadow-lg p-2 text-gray-800"
                     style="display: none;">
                    
                    {{-- 
                        PERUBAHAN 4: Link Mobile
                        - Menghapus {{ request()->is(...) }}
                        - Menggunakan @click untuk MENGATUR 'activeSection' DAN 'open'
                        - Menggunakan :class untuk MEMBACA 'activeSection'
                    --}}
                    <a href="/smartcity" 
                        @click="open = false; activeSection = 'beranda'"
                        :class="activeSection === 'beranda' ? 'text-blue-600 font-semibold' : ''"
                        class="block px-4 py-2 hover:bg-gray-100 rounded">
                        Beranda
                    </a>
                    <a href="/smartcity#smart"
                        @click="open = false; activeSection = 'pilar'"
                        :class="activeSection === 'pilar' ? 'text-blue-600 font-semibold' : ''"
                        class="block px-4 py-2 hover:bg-gray-100 rounded">
                        Pilar Kota Cerdas
                    </a>
                    <a href="/smartcity-informasi"
                        @click="open = false; activeSection = 'informasi'"
                        :class="activeSection === 'informasi' ? 'text-blue-600 font-semibold' : ''"
                        class="block px-4 py-2 hover:bg-gray-100 rounded">
                        Informasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>