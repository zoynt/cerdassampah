<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CerdasSampah.id</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- AOS Animate On Scroll -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/typeit@8.7.1/dist/index.umd.js"></script>
  
</head>
<body class="font-sans antialiased">

<!-- Header -->
<header class="flex justify-between items-center px-6 py-4 bg-gradient-to-r from-green-400 to-green-500 text-white">
  <div class="flex items-center space-x-2">
    <img src="/img/logo.png" alt="Logo" class="w-8 h-8">
    <span class="font-bold text-lg">CerdasSampah.id</span>
  </div>
  <nav class="space-x-6 hidden md:block">
    <a href="#" class="font-semibold">Beranda</a>
    <a href="#" class="font-semibold">Fitur</a>
    <a href="#" class="font-semibold">Informasi</a>
    <a href="#" class="font-semibold">Ayo Laporkan!</a>
  </nav>
</header>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-green-300 to-green-400 text-white py-20 px-6 md:flex md:items-center md:justify-between">
  <div class="max-w-xl">
  <h1 id="heroText" class="text-4xl font-bold tracking-tight text-white sm:text-6xl"></h1>

    <!-- <h1 class="text-4xl font-bold leading-tight mb-4">Solusi Cerdas untuk<br>Lingkungan Bersih</h1> -->
    <p class="mb-6">CerdasSampah.id adalah aplikasi website berbasis Smart Environment yang mendukung pilar Smart City melalui pengelolaan sampah yang inovatif dan partisipatif.</p>
    <a href="/login" class="bg-green-700 hover:bg-green-800 text-white py-2 px-4 rounded">Login</a>
  </div>
  <img src="/img/logo.png" alt="Hero Image" class="w-64 mt-8 md:mt-0" />
</section>

<!-- Pemetaan Section -->
<section class="py-20 px-6 bg-gray-50">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <div>
      <div id="map" class="w-full h-96 rounded border-4 border-green-400"></div>
    </div>
    <div>
      <h2 class="font-bold text-lg mb-2">Pemetaan TPS, TPS-T & TPS Liar</h2>
      <h3 class="text-2xl font-bold mb-2">Temukan Lokasi <span class="text-green-700">TPS, TPS-T & TPS Liar</span> di Sekitarmu</h3>
      <p class="mb-4">Sudah lebih dari 00 titik TPS, TPS-T & TPS Liar terdaftar di CerdasSampah.id!</p>
      <div class="space-y-2">
        <div class="bg-green-200 text-center py-2 rounded">TPS & TPS-T<br><span class="text-2xl font-bold">00</span></div>
        <div class="bg-green-100 text-center py-2 rounded">TPS Liar<br><span class="text-2xl font-bold">00</span></div>
      </div>
    </div>
  </div>
</section>

<!-- Scan Section -->
<section class="py-20 px-6">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h2 class="font-bold text-lg mb-2">Scan Sampah</h2>
      <h3 class="text-3xl font-bold mb-4">Kenali <span class="bg-green-300 px-2 rounded">Jenis Sampah</span> dengan Sekali Pindai</h3>
      <p class="mb-6">Fitur Scan memanfaatkan teknologi machine learning untuk mengidentifikasi sampah secara otomatis dan akurat.</p>
      <a href="/scan" class="bg-green-700 hover:bg-green-800 text-white py-2 px-4 rounded">Unggah Sekarang</a>
    </div>
    <div class="w-full md:w-1/2 flex justify-center">
    <i class="fas fa-camera text-green-600 text-[100px]"></i>
    </div>
    <!-- <img src="/images/scan-icon.png" alt="Scan Icon" class="w-64 mx-auto" /> -->
  </div>

  <div class="mt-16 bg-green-50 p-6 rounded-lg text-center">
    <h3 class="text-xl font-bold mb-4"><span class="bg-green-300 px-2 rounded">Langkah Mudah</span> Menggunakan Fitur Scan</h3>
    <div class="grid grid-cols-3 gap-4 mt-6">
      <div>
        <div class="bg-green-400 text-white rounded-full w-10 h-10 flex items-center justify-center mx-auto mb-2">1</div>
        <p class="font-semibold">Unggah Gambar</p>
      </div>
      <div>
        <div class="bg-green-400 text-white rounded-full w-10 h-10 flex items-center justify-center mx-auto mb-2">2</div>
        <p class="font-semibold">Analisis Sistem</p>
      </div>
      <div>
        <div class="bg-green-400 text-white rounded-full w-10 h-10 flex items-center justify-center mx-auto mb-2">3</div>
        <p class="font-semibold">Hasil Muncul</p>
      </div>
    </div>
  </div>
</section>

<!-- Game Section -->
<section class="py-20 px-6 bg-gray-50">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <img src="/images/game-icon.png" alt="Game Icon" class="w-64 mx-auto" />
    <div>
      <h2 class="font-bold text-lg mb-2">Game Pilah Sampah</h2>
      <h3 class="text-3xl font-bold mb-4"> <span class="bg-green-300 px-2 rounded">Belajar Pilah Sampah</span> dengan Cara yang Seru!</h3>
      <p class="mb-6">Game interaktif untuk belajar membedakan jenis sampah: organik, anorganik, dan B3 secara cepat dan tepat.</p>
      <a href="/game" class="bg-green-700 hover:bg-green-800 text-white py-2 px-4 rounded">Mainkan</a>
    </div>
  </div>
</section>

<!-- Video Edukasi -->
<section class="py-20 px-6 bg-gradient-to-r from-green-300 to-green-400 text-white text-center">
  <div class="max-w-4xl mx-auto">
    <h2 class="text-lg font-bold mb-4">Video Edukasi</h2>
    <div class="aspect-video bg-white rounded-lg overflow-hidden flex items-center justify-center">
      <img src="/images/video-thumbnail.png" alt="Video" class="w-32 opacity-80">
    </div>
    <p class="mt-6 text-xl font-semibold">Cara memilah sampah rumah tangga</p>
    <p class="mt-2 text-sm">Pelajari cara memilah dan mengelola sampah melalui konten edukatif yang menarik dan mudah dipahami.</p>
  </div>
</section>

<!-- Footer -->
<footer class="bg-gray-100 py-12 px-6">
  <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
    <div>
      <div class="flex items-center space-x-2 mb-4">
        <img src="/img/logo.png" alt="Logo" class="w-8 h-8">
        <span class="font-bold text-lg text-green-700">CerdasSampah.id</span>
      </div>
      <p class="text-sm text-gray-700">CerdasSampah.id merupakan inisiatif berbasis teknologi untuk mendukung konsep smart city.</p>
    </div>

    <div class="space-y-4">
      <div class="flex items-start space-x-2">
        <i class="fas fa-map-marker-alt text-green-700 mt-1 w-5"></i>
        <div>
          <h3 class="font-semibold text-sm">Alamat</h3>
          <p class="text-sm leading-snug">Komplek Andhika, Jl. Sultan Adam No.3, RW:Viaworkspace, Banjarmasin</p>
        </div>
      </div>
      <div class="flex items-start space-x-2">
        <i class="fas fa-envelope text-green-700 mt-1 w-5"></i>
        <div>
          <h3 class="font-semibold text-sm">Email</h3>
          <p class="text-sm">hello@via.co.id</p>
        </div>
      </div>
    </div>

    <div class="flex items-center space-x-4">
      <a href="#" class="text-green-700 text-xl hover:text-green-800"><i class="fab fa-youtube"></i></a>
      <a href="#" class="text-green-700 text-xl hover:text-green-800"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
  <p class="text-center text-sm text-gray-500 mt-10">&copy; Copyright 2025 CerdasSampah.id &mdash; All Rights Reserved.</p>
</footer>

<script>
  AOS.init();

  const map = L.map('map').setView([-3.3194, 114.5908], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  const sampleLocations = [
    [-3.318, 114.590],
    [-3.321, 114.595],
    [-3.320, 114.585]
  ];
  sampleLocations.forEach(coord => L.marker(coord).addTo(map));
</script>
<script>
  new TypeIt("#heroText", {
    speed: 60,
    waitUntilVisible: true,
    loop: true,
    breakLines: true,
    deleteSpeed: 40,
  })
  .type("Solusi Cerdas untuk")
  .break()
  .type("Lingkungan <strong>Bersih</strong>")
  .pause(1000)
  .delete(6)  // Hapus 'Bersih'
  .type("<strong>Asri</strong>")
  .pause(1000)
  .delete(4)  // Hapus 'Asri'
  .type("<strong>Indah</strong>")
  .pause(1000)
  .delete(5)  // Hapus 'Indah'
  .type("<strong>Nyaman</strong>")
  .pause(1000)
  .delete(6)  // Hapus 'Nyaman'
  .go();
</script>

</body>
</html>
