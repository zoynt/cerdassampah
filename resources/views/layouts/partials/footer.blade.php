<footer style="background-color: #f5f8f7; padding: 60px 0 0; color: #3b7555; font-family: 'Poppins', sans-serif; position: relative; overflow: visible;">
  <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px 40px; position: relative; z-index: 1;">

    {{-- Logo dan Kontak --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">

      {{-- Logo --}}
      <div class="flex items-center gap-4 flex-wrap justify-center">
        <img src="{{ asset('img/logosec.png') }}" alt="Logo" class="h-20 md:h-14 sm:h-10">
        <img src="{{ asset('img/textsec.png') }}" alt="Logo" class="h-40 md:h-28 sm:h-20">
        <!-- <span class="text-3xl md:2xl sm:xl font-bold">BapilahSampah</span> -->
      </div>
    </div>

    {{-- Tentang dan Sosial Media --}}
    <div style="font-size: 16px; line-height: 1.8;">
      <!-- <strong style="display: block; margin-bottom: 10px;">Tentang BapilahSampah</strong> -->
      <p style="margin: 0;">
        BapilahSampah merupakan inisiatif berbasis teknologi yang mendukung penerapan konsep smart city,
        khususnya dalam pilar smart environment. Melalui fitur-fitur cerdas seperti pemetaan TPS, scan sampah,
        hingga edukasi interaktif, platform ini hadir untuk mendorong pengelolaan sampah yang lebih tertib,
        partisipatif, dan berkelanjutan.
      </p>

      {{-- Ikon Sosial --}}
      <div style="margin-top: 20px;">
        <div style="display: flex; gap: 20px; align-items: center; padding-bottom: 36px;">
        </div>
      </div>
    </div>

    {{-- Copyright --}}
    <div style="text-align: center; margin-top: 35px; font-size: 16px;">
      © Copyright 2025 <strong>BapilahSampah</strong> – All Rights Reserved.
    </div>
  </div>

  {{-- Wave Background --}}
  <div style="position: absolute; bottom: 0; left: 0; width: 100%; z-index: 0; line-height: 0; transform: scaleY(0.55); transform-origin: bottom;">
    <svg width="100%" height="100%" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg">
      <path d="M 0,400 L 0,150 C 142.53571428571428,182.42857142857144 285.07142857142856,214.85714285714286 395,215
        C 504.92857142857144,215.14285714285714 582.2499999999999,183.00000000000003 701,180
        C 819.7500000000001,176.99999999999997 979.9285714285716,203.1428571428571 1110,203
        C 1240.0714285714284,202.8571428571429 1340.0357142857142,176.42857142857144 1440,150
        L 1440,400 L 0,400 Z"
        fill="#ffffff" fill-opacity="1"></path>
    </svg>
  </div>
</footer>



@push('style')
<style>
  @media (max-width: 768px) {
    footer {
      padding: 40px 0 0;
    }

    footer .flex {
      flex-direction: column !important;
      align-items: flex-start !important;
    }

    footer .flex.items-center.gap-4 {
      margin-bottom: 20px;
    }

    footer div[style*="max-width: 420px"] {
      margin-top: 10px !important;
      max-width: 100% !important;
    }

    footer div[style*="font-size: 16px"] {
      font-size: 15px !important;
    }

    footer div[style*="display: flex; gap: 20px; align-items: center"] {
      justify-content: flex-start !important;
      flex-wrap: wrap;
      gap: 16px !important;
    }

    footer .text-3xl {
      font-size: 1.5rem !important;
    }

    footer img.h-20 {
      height: 64px !important;
    }

    footer div[style*="text-align: center"] {
      font-size: 13px !important;
      padding-top: 20px;
    }

    footer svg {
      width: auto;
      height: auto;
      max-height: 36px;
    }

    footer div[style*="padding-bottom: 36px"] {
      padding-bottom: 24px !important;
    }

    footer div[style*="display: flex; justify-content: space-between"] {
      gap: 20px !important;
    }
  }
</style>

@endpush
