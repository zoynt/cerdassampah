<footer style="background-color: #f5f8f7; padding: 60px 0 0; color: #3b7555; font-family: 'Poppins', sans-serif; position: relative; overflow: visible;">
  <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px 40px; position: relative; z-index: 1;">

    {{-- Logo dan Kontak --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">

      {{-- Logo --}}
      <div class="flex items-center gap-4 flex-wrap justify-center">
        <img src="{{ asset('img/logobiasa.png') }}" alt="Logo" class="h-20 md:h-14 sm:h-10">
        <span class="text-3xl md:2xl sm:xl font-bold">CerdasSampah.id</span>
      </div>

      {{-- Kontak --}}
      <div style="max-width: 420px; display: flex; flex-direction: column; margin-top: 20px; gap: 14px; font-size: 14px;">

        {{-- Alamat --}}
        <div style="display: flex; gap: 10px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned-icon lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
          <div>
            <strong>Alamat</strong><br>
            Komplek Andhika, Jl. Sultan Adam No.3, RW.Viaworkspace,<br>
            Sungai Miai, Banjarmasin Utara, Banjarmasin City,<br>
            South Kalimantan 70123
          </div>
        </div>

        {{-- Email --}}
        <div style="display: flex; gap: 10px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
          <div>
            <strong>Email</strong><br>
            hello@via.co.id
          </div>
        </div>
      </div>
    </div>

    {{-- Garis Pembatas --}}
    <hr style="margin: 35px 0 25px; border: none; border-top: 1.5px solid #3b7555;">

    {{-- Tentang dan Sosial Media --}}
    <div style="font-size: 16px; line-height: 1.8;">
      <strong style="display: block; margin-bottom: 10px;">Tentang CerdasSampah.id</strong>
      <p style="margin: 0;">
        CerdasSampah.id merupakan inisiatif berbasis teknologi yang mendukung penerapan konsep smart city,
        khususnya dalam pilar smart environment. Melalui fitur-fitur cerdas seperti pemetaan TPS, scan sampah,
        hingga edukasi interaktif, platform ini hadir untuk mendorong pengelolaan sampah yang lebih tertib,
        partisipatif, dan berkelanjutan.
      </p>

      {{-- Ikon Sosial --}}
      <div style="margin-top: 20px;">
        <div style="display: flex; gap: 20px; align-items: center; padding-bottom: 36px;">
        <a href="https://youtube.com/cerdassampah" target="_blank" style="color: inherit; text-decoration: none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            style="transform: scale(1.2); transform-origin: center;">
            <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17" />
            <path d="m10 15 5-3-5-3z" />
          </svg>
        </a>
        <a href="https://instagram.com/cerdassampah" target="_blank" style="color: inherit; text-decoration: none;">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-instagram-icon lucide-instagram">
        <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
      </svg>
    </a>
        </div>
      </div>
    </div>

    {{-- Copyright --}}
    <div style="text-align: center; margin-top: 35px; font-size: 16px;">
      © Copyright 2025 <strong>CerdasSampah.id</strong> – All Rights Reserved.
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
