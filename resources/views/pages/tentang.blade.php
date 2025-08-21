@extends('layouts.landing')

@section('content')

    <div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] z-[-999]">
      <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 490" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150">
        <path d="M 0,500 L 0,187 C 257.5,150.5 515,114 755,114 C 995,114 1217.5,150.5 1440,187 L 1440,500 L 0,500 Z" stroke="none" stroke-width="0" fill="#60cb83" fill-opacity="1" class="transition-all duration-300 ease-in-out delay-150 path-0" transform="rotate(-180 720 250)"></path>
      </svg>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 py-20 lg:py-32 flex flex-col-reverse lg:flex-row items-center justify-between gap-10 overflow-hidden" style="padding-bottom: 48px;">
      <!-- Konten Kiri -->
      <div class="w-full text-center">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mt-6 mb-12 text-white" data-aos="zoom-in">
          Tentang
        </h1>
        <div class="bg-white rounded-xl shadow-md p-8" data-aos="fade-up">
        <div class="flex flex-col items-center md:items-start sm:items-start">
          <h3 class="text-xl text-white bg-green-600 font-semibold mb-4 p-4 inline-block rounded-xl text-center md:text-left sm:text-left">CerdasSampah.id</h3>
          <p class="text-lg" style="text-align: justify;">CerdasSampah adalah platform digital yang dirancang untuk membantu masyarakat mengelola sampah dengan cara yang lebih cerdas, praktis, dan interaktif. Melalui berbagai fitur unggulan seperti pemindaian sampah, pemetaan lokasi TPS beserta jadwal pengangkutannya, game pilah sampah, hingga pelaporan TPS ilegal, CerdasSampah hadir untuk menghubungkan edukasi, teknologi, dan aksi nyata di lapangan.</p>
        </div>
        </div>
      </div>
    </div>

    <!-- 2 Card Section Below -->
    <div class="max-w-7xl mx-auto px-6 lg:px-12" style="padding-top: 0px;">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Card 1 (Tujuan Kami) -->
        <div class="bg-white rounded-xl shadow-md p-8" data-aos="fade-up">
        <div class="flex flex-col items-center md:items-start sm:items-start"> <!-- Flexbox disini untuk menata item secara vertikal dan horizontal -->
          <h3 class="text-xl text-white bg-green-600 font-semibold mb-4 p-4 inline-block rounded-xl text-center sm:text-left">Tujuan Kami</h3>
          <ol class="list-decimal pl-6 text-black text-lg" style="text-align: justify; padding-left: 20px; list-style-type: decimal;">
            <li>Meningkatkan kesadaran masyarakat dalam pemilahan sampah dari rumah.</li>
            <li>Mempermudah akses informasi TPS dan jadwal pengangkutan agar pengelolaan sampah lebih teratur.</li>
            <li>Menghadirkan edukasi interaktif melalui game pilah sampah yang seru dan mudah dipahami.</li>
            <li>Mendorong partisipasi warga untuk melaporkan penumpukan sampah atau TPS ilegal dengan cepat.</li>
            <li>Menukung terciptanya kota yang bersih dan berkelanjutan sebagai bagian dari upaya menuju Smart City.</li>
          </ol>
        </div>
        </div>

        <!-- Card 2 (Kontak Pengaduan dan Bantuan dengan Ikon) -->
        <div class="bg-white rounded-xl shadow-md p-8" data-aos="fade-up">
        <div class="flex flex-col items-center md:items-start sm:items-start"> <!-- Flexbox disini untuk menata item secara vertikal dan horizontal -->
          <h3 class="text-xl text-white bg-green-600 font-semibold mb-4 p-4 inline-block rounded-xl text-center sm:text-left">Kontak Pengaduan dan Bantuan</h3>
          <p class="text-lg mb-4" style="text-align: justify;">Jika Anda membutuhkan bantuan atau ingin memberikan laporan pengaduan terkait pengelolaan sampah, Anda dapat menghubungi kami melalui:</p>
          <div class="space-y-4">
            <div class="flex items-center mb-2">
              <i class="fas fa-phone-alt text-green-600" style="margin-right: 15px; font-size: 1.5rem;"></i> <!-- Margin lebih besar antara ikon dan teks -->
              <span class="text-lg">+628123456789</span>
            </div>
            <div class="flex items-center mb-2">
              <i class="fas fa-envelope text-green-600" style="margin-right: 15px; font-size: 1.5rem;"></i>
              <span class="text-lg">hello@via.co.id</span>
            </div>
            <div class="flex items-center mb-2">
              <i class="fab fa-instagram text-green-600" style="margin-right: 15px; font-size: 1.5rem;"></i>
              <span class="text-lg">viaworkspace</span>
            </div>
            <div class="flex items-center mb-2">
              <i class="fas fa-sitemap text-green-600" style="margin-right: 15px; font-size: 1.5rem;"></i>
              <span class="text-lg"><a href="https://viaworkspace.com" target="_blank" class="text-green-500">viaworkspace.com</a></span>
            </div>
            <div class="flex items-center mb-2">
              <i class="fab fa-youtube text-green-600" style="margin-right: 15px; font-size: 1.5rem;"></i>
              <span class="text-lg">viaworkspace</span>
            </div>
            <div class="flex items-center mb-2">
              <i class="fab fa-linkedin text-green-600" style="margin-right: 15px; font-size: 1.5rem;"></i>
              <span class="text-lg">PT. Via Digital Indonesia - via.co.id</span>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>

    <!-- New Section: Tim CerdasSampah.id -->
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-20">
        <div class="flex justify-center items-center w-full">
            <h3 class="text-xl font-semibold text-center mb-8 text-white bg-green-600 rounded-xl px-6 py-4" data-aos="zoom-in">
                Tim CerdasSampah.id
            </h3>
        </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-xl shadow-md text-center" data-aos="fade-up" data-aos-delay="100">
          <img src="img/zuya.jpg" alt="Zoya Nujula Ramadhoni" class="card-img mx-auto mb-4"> <!-- Circle for image -->
          <h4 class="font-semibold text-lg mb-2">Zoya Nujula Ramadhoni</h4>
          <p class="text-gray-500">Project Manager & Data Scientist</p>
        </div>
        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-xl shadow-md text-center" data-aos="fade-up" data-aos-delay="300">
          <img src="img/irfan.jpg" alt="Muhammad Irfan Akbar" class="card-img mx-auto mb-4"> <!-- Circle for image -->
          <h4 class="font-semibold text-lg mb-2">Muhammad Irfan Akbar</h4>
          <p class="text-gray-500">Full Stack Developer</p>
        </div>
        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-xl shadow-md text-center" data-aos="fade-up" data-aos-delay="600">
          <img src="img/nisa.png" alt="Anissa Nurrahmah" class="card-img mx-auto mb-4"> <!-- Circle for image -->
          <h4 class="font-semibold text-lg mb-2">Anissa Nurrahmah</h4>
          <p class="text-gray-500">UI/UX Designer & Back-End Developer</p>
        </div>
      </div>
    </div>

  <style>
    html, body {
      overflow-x: hidden;
    }

    /* Additional adjustments for spacing and responsiveness */
    @media (max-width: 768px) {
      .flex-col-reverse {
        flex-direction: column-reverse;
      }
      .lg\:py-32 {
        padding-top: 2rem;
        padding-bottom: 2rem;
      }
    }

    /* Font Awesome Icons */
    .fa-phone-alt, .fa-envelope, .fa-link, .fa-instagram, .fa-youtube, .fa-sitemap {
      font-size: 1.25rem;
    }

    .card-img {
  width: 250px;        /* Sesuaikan ukuran gambar */
  height: 250px;       /* Sesuaikan ukuran gambar */
  border-radius: 50%;  /* Membuat gambar berbentuk lingkaran */
  object-fit: cover;   /* Menjaga agar gambar mengisi kontainer tanpa distorsi */
  object-position: center; /* Menjaga gambar tetap terpusat di dalam lingkaran */
}



  </style>

@endsection
