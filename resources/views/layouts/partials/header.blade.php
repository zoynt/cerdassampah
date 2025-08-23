<header id="main-header" class="font-sans fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo + Brand -->
            <!-- <div class="flex items-center space-x-3">
        <img src="/img/logoputih.png" alt="Logo" class="h-8 w-8" /> -->
            <!-- <a href="/#beranda" class="px-4 py-2 rounded-md hover:bg-green-700 transition"></a> -->
            <!-- <img src="/img/textbs.png" alt="Logo" class="h-48 w-auto" /> -->
            <!-- <span class="text-white font-bold text-lg">CerdasSampah.id</span>
      </div> -->
            <div class="flex items-center space-x-3">
                <!-- Membuat Logo menjadi Link ke Beranda -->
                <a href="/#beranda" class="flex items-center">
                    <img src="/img/logoputih.png" alt="Logo" class="h-8 w-8" style="margin-right: 16px;" />
                    <span class="text-white font-bold text-lg">CerdasSampah.id</span>
                </a>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="lg:hidden">
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>

            <!-- Navigation Menu (Desktop) -->
            <nav id="main-nav" class="hidden lg:flex flex-row items-center space-x-4 text-sm font-semibold text-white">
                <a href="/" class="px-4 py-2 rounded-md hover:bg-green-700 transition">Beranda</a>

        <!-- Dropdown -->
        <div class="relative group">
          <div class="inline-flex items-center px-4 py-2 rounded-md hover:bg-green-700 cursor-pointer transition">
            <span>Fitur</span>
            <svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.1 1.02l-4.25 4.65a.75.75 0 01-1.1 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
            </svg>
          </div>
          <!-- Dropdown Menu -->
          <div class="absolute left-0 mt-2 w-44 bg-white text-gray-800 shadow-md rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transform scale-95 group-hover:scale-100 transition-all duration-200 ease-out z-20">
          <a href="/#peta" class="block px-6 py-2 hover:bg-green-100">Peta TPS, TPS-T3R & TPS Liar</a>
          <a href="/#scan" class="block px-6 py-2 hover:bg-green-100">Scan Sampah</a>
          <a href="/#game" class="block px-6 py-2 hover:bg-green-100">Game Pilah Sampah</a>
          <a href="/#edukasi" class="block px-6 py-2 hover:bg-green-100">Video Edukasi</a>
          <a href="/#faq" class="block px-6 py-2 hover:bg-green-100">FAQ's</a>
          </div>
        </div>
        <a href="{{ route('informasi') }}" class="px-4 py-2 rounded-md hover:bg-green-700 transition">Informasi</a>
        <a href="{{ route('tentang') }}" class="px-4 py-2 rounded-md hover:bg-green-700 transition">Tentang</a>
        
        {{-- lapor --}}
        @auth
            <a href="{{ route('lapor.index') }}" class="px-4 py-2 rounded-md hover:bg-green-700 transition font-bold">Ayo Laporkan!</a>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2 rounded-md hover:bg-green-700 transition font-bold">Ayo Laporkan!</a>
        @endauth

        <div class="h-6 w-px bg-white/70"></div>
        <a href="{{ route('login') }}" class="px-4 py-2 rounded-md hover:bg-green-700 transition">Masuk</a>
      </nav>


    </div>

        <!-- Mobile Nav Menu -->
        <div id="mobile-nav"
            class="lg:hidden overflow-hidden max-h-0 transition-all duration-500 ease-in-out bg-white rounded-md p-0 text-gray-800">
            <a href="/" class="block px-4 py-2 hover:bg-green-100 rounded">Beranda</a>

            <div class="border-t border-gray-200"></div>

            <!-- Fitur Toggle -->
            <button id="fitur-toggle"
                class="w-full text-left px-4 py-2 font-semibold hover:bg-green-100 flex justify-between items-center">
                <span>Fitur</span>
                <svg id="fitur-icon" class="h-4 w-4 transition-transform duration-200" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.1 1.02l-4.25 4.65a.75.75 0 01-1.1 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Submenu Fitur -->
            <div id="fitur-submenu" class="hidden">
                <a href="/#peta" class="block px-6 py-2 hover:bg-green-100">Peta TPS, TPS-T3R & TPS Liar</a>
                <a href="/#scan" class="block px-6 py-2 hover:bg-green-100">Scan Sampah</a>
                <a href="/#game" class="block px-6 py-2 hover:bg-green-100">Game Pilah Sampah</a>
                <a href="/#edukasi" class="block px-6 py-2 hover:bg-green-100">Video Edukasi</a>
                <a href="/#faq" class="block px-6 py-2 hover:bg-green-100">FAQ's</a>
            </div>

            <div class="border-t border-gray-200"></div>
            <a href="{{ route('informasi') }}" class="block px-4 py-2 hover:bg-green-100 rounded">Informasi</a>
            <a href="{{ route('tentang') }}" class="block px-4 py-2 hover:bg-green-100 rounded">Tentang</a>
            @auth
                <a href="{{ route('lapor.index') }}" class="block px-4 py-2 hover:bg-green-100 font-bold rounded">Ayo Laporkan!</a>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-green-100 rounded">Ayo Laporkan!</a>
            @endauth
            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-green-100 rounded">Masuk</a>
        </div>
    </div>
</header>

<script>
    const menuButton = document.getElementById("mobile-menu-button");
    const menu = document.getElementById("mobile-nav");
    let menuOpen = false;

    menuButton.addEventListener("click", () => {
        if (menuOpen) {
            menu.style.maxHeight = "0";
            menu.style.visibility = "hidden";
            menu.style.padding = "0";
        } else {
            menu.style.maxHeight = "500px";
            menu.style.visibility = "visible";
            menu.style.padding = "1rem";
        }
        menuOpen = !menuOpen;
    });

    // Submenu Fitur Toggle
    const fiturToggle = document.getElementById("fitur-toggle");
    const fiturSubmenu = document.getElementById("fitur-submenu");
    const fiturIcon = document.getElementById("fitur-icon");

    let fiturOpen = false;
    fiturToggle.addEventListener("click", () => {
        fiturSubmenu.classList.toggle("hidden");
        fiturIcon.classList.toggle("rotate-180");
        fiturOpen = !fiturOpen;
    });
</script>

