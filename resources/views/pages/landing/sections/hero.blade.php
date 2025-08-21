<section id="beranda" class="custom-gradient relative text-white overflow-hidden">
  <!-- Background Dekoratif -->
  <div class="absolute top-0 left-0 w-full h-full z-0 pointer-events-none overflow-hidden max-w-screen">
    <div class="absolute top-10 left-[5%] md:left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-[5%] md:right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-ping"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 py-20 lg:py-32 flex flex-col-reverse lg:flex-row items-center justify-between gap-10 overflow-hidden">
    <!-- Konten Kiri -->
    <div class="w-full lg:w-1/2 text-center lg:text-left" data-aos="fade-right">
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-6 text-white">
        Solusi Cerdas untuk<br>
        Lingkungan <span id="typed-kata" class="inline-block text-white"></span>
      </h1>
      <p class="text-sm sm:text-base md:text-lg mb-8 text-white/90">
        CerdasSampah.id adalah aplikasi website berbasis Smart Environment yang mendukung pilar Smart City melalui pengelolaan sampah yang inovatif dan partisipatif. Dirancang untuk meningkatkan kesadaran serta keterlibatan masyarakat.
      </p>
      <a href="{{ route('register') }}" class="inline-block bg-white text-green-700 font-semibold px-6 py-3 rounded-md shadow hover:bg-green-100 transition duration-300">
        Masuk
      </a>
    </div>

    <!-- Gambar Kanan -->
    <div class="w-full lg:w-1/2 flex justify-center" data-aos="fade-left">
      <img src="{{ asset('img/logobiasa.png') }}" alt="Maskot CerdasSampah" class="w-[300px] md:w-[360px] lg:w-[400px] animate-float">
    </div>
  </div>

  <!-- Wave Animasi -->
  <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] pointer-events-none z-0">
    <div class="relative w-full h-[200px] md:h-[250px] lg:h-[280px]">
      <!-- Wave 1 -->
      <svg class="absolute bottom-0 w-full h-full animate-wave-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#e6fff5" fill-opacity="1" d="M0,256L80,250.7C160,245,320,235,480,229.3C640,224,800,224,960,229.3C1120,235,1280,245,1360,250.7L1440,256L1440,320L0,320Z" />
      </svg>
      <!-- Wave 2 -->
      <svg class="absolute bottom-0 w-full h-full animate-wave-2 delay-300 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#f0fdf4" fill-opacity="1" d="M0,288L80,272C160,256,320,224,480,208C640,192,800,192,960,208C1120,224,1280,256,1360,272L1440,288L1440,320L0,320Z" />
      </svg>
      <!-- Wave 3 -->
      <svg class="absolute bottom-0 w-full h-full animate-wave-3 delay-500 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#ffffff" fill-opacity="1" d="M0,240L80,218.7C160,197,320,155,480,149.3C640,144,800,176,960,202.7C1120,229,1280,251,1360,261.3L1440,272L1440,320L0,320Z" />
      </svg>
    </div>
  </div>
</section>

<!-- TypeIt -->
@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    new TypeIt("#typed-kata", {
      speed: 100,
      waitUntilVisible: true,
      loop: true
    })
    .type("Bersih", { delay: 1200 })
    .delete(null, { delay: 500 })
    .type("Indah", { delay: 1200 })
    .delete(null, { delay: 500 })
    .type("Asri", { delay: 1200 })
    .delete(null, { delay: 500 })
    .type("Nyaman", { delay: 1200 })
    .go();
  });
</script>
@endpush

<!-- Style tambahan -->
@push('styles')
<style>
html, body {
  overflow-x: hidden;
}
.custom-gradient {
    /* background: linear-gradient(to right, #93DA97 0%, #60CB83 25%, #93DA97 50%); */
    background: #60CB83;
  }
@keyframes waveFloat1 {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(15px); }
}
@keyframes waveFloat2 {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(25px); }
}
@keyframes waveFloat3 {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(35px); }
}
.animate-wave-1 { animation: waveFloat1 8s ease-in-out infinite; }
.animate-wave-2 { animation: waveFloat2 10s ease-in-out infinite; }
.animate-wave-3 { animation: waveFloat3 12s ease-in-out infinite; }
.delay-300 { animation-delay: 0.3s; }
.delay-500 { animation-delay: 0.5s; }

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-12px); }
}
.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>
@endpush
