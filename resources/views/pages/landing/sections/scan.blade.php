@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
<style>
  .highlight-animate {
    position: relative;
    display: inline-block;
    color: white;
    padding: 0 0.4rem;
    border-radius: 0.25rem;
    background-color: transparent;
    z-index: 0;
    overflow: hidden;
    vertical-align: middle;
  }
  .highlight-animate::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0;
    background-color: #15803d;
    z-index: -1;
    border-radius: 0.25rem;
    transition: width 2.5s ease-out;
  }
  .highlight-animate.active::before {
    width: 100%;
  }
  .scanner-line {
    position: absolute;
    height: 6px;
    width: 100%;
    left: 0;
    top: 0%;
    animation: scanY 2s infinite ease-in-out;
    z-index: 5;
    background: linear-gradient(to right, transparent, #4ade80, transparent);
    box-shadow: 0 0 12px 4px rgba(34, 197, 94, 0.6);
    border-radius: 10px;
    filter: blur(0.5px);
    opacity: 0.85;
  }

  @keyframes scanY {
    0% { top: 0%; }
    50% { top: 100%; }
    100% { top: 0%; }
  }

  @media (min-width: 320px) {
    #scan-icon {
      width: 6rem;
      height: 6rem;
    }
  }

  @media (min-width: 375px) {
    #scan-icon {
      width: 8rem;
      height: 8rem;
    }
  }

  @media (min-width: 425px) {
    #scan-icon {
      width: 10rem;
      height: 10rem;
    }
  }

  @media (min-width: 768px) {
    #scan-icon {
      width: 12rem;
      height: 12rem;
    }
  }

  @media (min-width: 1024px) {
    #scan-icon {
      width: 14rem;
      height: 14rem;
    }
  }

  @media (min-width: 1440px) {
    #scan-icon {
      width: 18rem;
      height: 18rem;
    }
  }
</style>
@endpush

<section id="scan" class="relative z-10 mb-12 pb-12 bg-white">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-1">

      <h2 class="text-xl font-bold text-green-900 tracking-wide mb-8 border-b-2 border-green-700 w-fit" data-aos="fade-down">
        Scan Sampah
      </h2>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start mb-12 pb-6">
      <div class="lg:col-span-1 text-gray-800 mt-6 lg:mt-0 " data-aos="fade-right">
        <h3 class="text-2xl md:text-3xl leading-tight align-middle mb-4">
          Kenali Semua
          <span class="highlight-animate">Jenis Sampah</span>
          dengan Pemindaian
          Cerdas Berbasis AI
        </h3>
        <p class="text-gray-600 mt-0 mb-8 text-lg md:text-xl leading-normal">
          Kenali jenis sampah hanya dengan satu kali pindai. Fitur Scan Sampah memanfaatkan teknologi machine learning untuk mengidentifikasi sampah organik, anorganik, dan berbahaya (B3) secara otomatis dan akurat.
        </p>
        <a href="{{ route('scan.form') }}" class="inline-block bg-green-700 text-white font-semibold px-6 py-3 rounded-md shadow hover:bg-green-800 transition duration-300">
          Pindai Sekarang!
        </a>
      </div>

<!-- Icon Scan -->
<div class="flex justify-center relative mx-auto scan-icon" data-aos="zoom-in">
  <!-- Garis scanner horizontal -->
  <div class="scanner-line"></div>

  <!-- Ikon di tengah -->
  <svg xmlns="http://www.w3.org/2000/svg" class="text-green-700 z-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" id="scan-icon">
    <path stroke-linecap="round" stroke-linejoin="round"
    d="M3 7V5a2 2 0 012-2h2M21 7V5a2 2 0 00-2-2h-2M3 17v2a2 2 0 002 2h2M21 17v2a2 2 0 01-2 2h-2M8 12h8M12 16V8" />
  </svg>
</div>


    </div>

    <!-- Steps -->
    <div class="mt-16 bg-green-50 p-8 rounded-xl relative z-10" data-aos="zoom-in">
      <h2 class="text-2xl md:text-3xl text-center mb-4">
        <span class="highlight-animate">Langkah Mudah</span>
        <!-- <span class="bg-green-700 text-white px-2 py-1 rounded inline-block">Langkah Mudah</span> -->
        Menggunakan Fitur Scan
      </h2>
      <p class="text-center text-gray-700 max-w-2xl mx-auto mb-12">
        Unggah foto sampah, dalam tiga langkah sederhana, Anda dapat mengetahui apakah sampah tersebut tergolong organik, anorganik, atau berbahaya.
      </p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <!-- Step 1 -->
        <div data-aos="zoom-in" data-aos-delay="0">
          <div class="w-12 h-12 mx-auto bg-green-600 text-white flex items-center justify-center rounded-full text-lg font-bold mb-4">1</div>
          <h3 class="font-bold mb-2">Unggah Gambar Sampah</h3>
          <p class="text-sm text-gray-600">
            Ambil foto sampah menggunakan kamera langsung dari aplikasi atau unggah gambar dari galeri.
          </p>
        </div>
        <!-- Step 2 -->
        <div data-aos="zoom-in" data-aos-delay="500">
          <div class="w-12 h-12 mx-auto bg-green-600 text-white flex items-center justify-center rounded-full text-lg font-bold mb-4">2</div>
          <h3 class="font-bold mb-2">Sistem Menganalisis Gambar</h3>
          <p class="text-sm text-gray-600">
            Sistem kami akan memproses gambar dan mengenali jenis sampah berdasarkan pola visual.
          </p>
        </div>
        <!-- Step 3 -->
        <div data-aos="zoom-in" data-aos-delay="1000">
          <div class="w-12 h-12 mx-auto bg-green-600 text-white flex items-center justify-center rounded-full text-lg font-bold mb-4">3</div>
          <h3 class="font-bold mb-2">Hasil Analisis Ditampilkan</h3>
          <p class="text-sm text-gray-600">
            Hasil klasifikasi akan muncul secara otomatis (Organik, anorganik dan berbahaya B3)
          </p>
        </div>
      </div>
    </div>

  </div>
</section>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
@endpush