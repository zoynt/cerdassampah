<section id="game" class="relative z-10 mb-12 pb-12 bg-white">
  <div class="max-w-7xl mx-auto px-4">
    <!-- Judul Atas -->
    <div class="grid grid-cols-1 lg:grid-cols-4">
      <div class="hidden lg:block lg:col-span-3"></div>
      <div class="flex justify-end">
        <h2 class="text-xl font-bold text-green-900 tracking-wide mb-8 border-b-2 border-green-700 text-right w-fit" data-aos="fade-down">
          Game Pilah Sampah
        </h2>
      </div>
    </div>

    <!-- Hero Game -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start mb-12 pb-6">
      <!-- Icon Gamepad -->
      <div class="flex justify-center relative mx-auto game-icon" data-aos="zoom-in">
        <img src="img/console.png" alt="Icon Gamepad" class="z-10 tilt-animation game-icon" />
      </div>

      <!-- Deskripsi Game -->
      <div class="lg:col-span-1 text-gray-800 mt-6 lg:mt-0" data-aos="fade-left">
        <h3 class="text-2xl md:text-3xl leading-tight align-middle mb-4">
          Ayo <span class="highlight-animate active">Belajar Pilah Sampah</span> dengan Cara yang Seru!
        </h3>
        <p class="text-gray-600 mt-0 mb-8 text-lg md:text-xl leading-normal">
          Uji pengetahuanmu tentang jenis sampah melalui permainan interaktif yang seru dan mendidik. Game Pilah Sampah mengajakmu untuk memilah sampah ke dalam kategori organik, anorganik, dan berbahaya (B3) dengan cepat dan tepat.
        </p>
        <a href="#masuk" class="inline-block bg-green-700 text-white font-semibold px-6 py-3 rounded-md shadow hover:bg-green-800 transition duration-300">
          Mainkan Sekarang!
        </a>
      </div>
    </div>

    <div class="relative bg-green-300 rounded-xl overflow-hidden px-6 lg:px-16 py-12 mt-16 z-0" data-aos="zoom-in">
      <!-- Background kota kiri -->
      <img src="/img/kota200.png" alt="Kota Kiri"
        class="absolute bottom-0 left-0 w-[300px] z-[-1] pointer-events-none select-none" />

      <!-- Background kota kanan -->
      <img src="/img/kota400.png" alt="Kota Kanan"
        class="absolute bottom-0 right-0 w-[600px] z-[-1] pointer-events-none select-none" />

      <!-- Konten utama -->
      <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between">
        <!-- kiri: teks -->
        <div class="text-center lg:text-left lg:w-1/2 mb-10 lg:mb-0 px-4 md:px-6 lg:px-12 xl:px-16 2xl:px-20" data-aos="fade-right">
          <h2 class="text-4xl md:text-5xl font-extrabold text-green-900 mb-4 leading-tight">Kumpulkan Poin</h2>
          <h3 class="text-2xl md:text-3xl font-extrabold text-white px-5 py-2 highlight-animate mb-4">Dapatkan Hadiah!</h3>
          <p class="text-green-900 text-md md:text-lg mb-4">Semakin cepat dan tepat kamu bermain, semakin banyak poin yang kamu raih. Tukar poinmu dengan reward menarik atau badge edukatif, dan jadilah Pahlawan Sampah Kota</p>
          <p class="text-green-900 font-medium mb-8 underline underline-offset-2">Main berkali-kali untuk tingkatkan skor dan koleksi hadiahnya!</p>
        </div>

        <!-- kanan: podium -->
        <div class="lg:w-1/2 flex justify-center relative" data-aos="zoom-in">
          <div class="bg-white rounded-xl shadow-md px-6 py-8 w-[300px] text-center">
            <div class="flex justify-around items-end relative w-full mx-auto">
              <!-- podium 2 -->
              <div data-aos="fade-up" data-aos-delay="500">
                <div class="flex flex-col items-center w-1/3 animate-delay-1">
                  <div class="w-10 h-10 rounded-full bg-green-700 text-white flex justify-center items-center mb-2 z-10">
                    <i class="fas fa-user text-sm"></i>
                  </div>
                  <div class="relative w-full">
                    <img src="/img/2.png" alt="Podium 2" class="w-full" />
                    <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-white font-bold text-sm">2</div>
                  </div>
                </div>
              </div>

              <!-- podium 1 -->
              <div data-aos="fade-up" data-aos-delay="1000">
                <div class="flex flex-col items-center w-1/3 animate-delay-2">
                  <div class="w-12 h-12 rounded-full bg-green-700 text-white flex justify-center items-center mb-2 z-10 shadow-md">
                    <i class="fas fa-user text-base"></i>
                  </div>
                  <div class="relative w-full">
                    <img src="/img/1.png" alt="Podium 1" class="w-full" />
                    <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-white font-bold text-sm">1</div>
                  </div>
                </div>
              </div>

              <!-- podium 3 -->
              <div data-aos="fade-up" data-aos-delay="50">
                <div class="flex flex-col items-center w-1/3 animate-delay-3">
                  <div class="w-10 h-10 rounded-full bg-green-700 text-white flex justify-center items-center mb-2 z-10">
                    <i class="fas fa-user text-sm"></i>
                  </div>
                  <div class="relative w-full">
                    <img src="/img/3.png" alt="Podium 3" class="w-full" />
                    <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-white font-bold text-sm">3</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-8 text-green-800 text-2xl font-bold">Pahlawan Kota!</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('styles')
  <style>
    @keyframes tiltRight {
      0%   { transform: rotate(0deg); }
      50%  { transform: rotate(20deg); }
      100% { transform: rotate(0deg); }
    }

    .tilt-animation {
      animation: tiltRight 2s ease-in-out infinite;
      transform-origin: center center;
    }

    /* Responsif untuk ikon game */
    @media (min-width: 320px) {
      .game-icon {
        width: 8rem;
        height: 8rem;
      }
    }

    @media (min-width: 375px) {
      .game-icon {
        width: 10rem;
        height: 10rem;
      }
    }

    @media (min-width: 425px) {
      .game-icon {
        width: 12rem;
        height: 12rem;
      }
    }

    @media (min-width: 768px) {
      .game-icon {
        width: 14rem;
        height: 14rem;
      }
    }

    @media (min-width: 1024px) {
      .game-icon {
        width: 16rem;
        height: 16rem;
      }
    }

    @media (min-width: 1440px) {
      .game-icon {
        width: 22rem;
        height: 22rem;
      }
    }
  </style>
@endpush
