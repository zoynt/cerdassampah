@extends('layouts.app')

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
          Informasi
        </h1>

            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-left">
                <!-- Judul dan Penjelasan Menggunakan Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex judul">
                        <h2 class="text-xl text-white bg-green-600 font-semibold p-4 inline-block rounded-xl">
                            Pentingnya Pengelolaan Sampah yang Bijak
                        </h2>
                    </div>

                    <!-- Gambar di Kanan -->
                    <div class="flex justify-center items-center">
                        <p class="text-lg hidden" style="text-align: justify;">
                        </p>
                    </div>
                </div>

                <!-- Gambar dan Penjelasan pada Mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Gambar di Kanan (di mobile) -->
                    <div class="text-left">
                        <p class="text-lg" style="text-align: justify;">
                            Pengelolaan sampah yang tepat menjadi kunci dalam menciptakan lingkungan yang sehat dan berkelanjutan.
                            Dengan meningkatnya volume sampah rumah tangga setiap harinya, dibutuhkan kesadaran kolektif untuk memilah,
                            mengurangi, dan mendaur ulang sampah. Melalui teknologi dan informasi yang tersedia di CerdasSampah,
                            masyarakat dapat mengambil peran aktif dalam menjaga kebersihan lingkungan.
                        </p>
                    </div>

                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex justify-center items-center">
                        <img src="img/info1.png" alt="Ilustrasi Warna Tempat Sampah" class="img">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-right">
                <!-- Judul dan Penjelasan Menggunakan Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                    <!-- Gambar di Kanan -->
                    <div class="flex justify-center items-center">
                        <p class="text-lg hidden" style="text-align: justify;">
                            </p>
                        </div>
                        <!-- Judul dan Penjelasan di Kiri -->
                        <div class="flex judul">
                            <h2 class="text-xl text-white bg-green-600 font-semibold p-4 inline-block rounded-xl">
                                Pengenalan Warna Tempat Sampah
                            </h2>
                        </div>
                </div>

                <!-- Gambar dan Penjelasan pada Mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex justify-center items-center">
                        <img src="img/info2.png" alt="Ilustrasi Warna Tempat Sampah" class="img">
                    </div>

                    <!-- Gambar di Kanan (di mobile) -->
                    <div class="text-left">
                        <!-- Penjelasan -->
                        <p class="text-lg" style="text-align: justify;">
                            Sistem warna pada tempat sampah membantu masyarakat memilah sampah dengan lebih mudah dan tepat. Setiap warna memiliki makna dan jenis sampah tertentu yang harus dibuang ke dalamnya. Tempat sampah
                            <span class="text-green">warna hijau</span> digunakan untuk sampah organik seperti sisa makanan dan daun kering.
                            <span class="text-yellow">Warna kuning</span> diperuntukkan bagi sampah anorganik seperti plastik, kaleng, dan kaca. Sementara itu,
                            <span class="text-red">warna merah</span> biasanya menandakan sampah berbahaya atau B3, seperti baterai bekas, obat kadaluwarsa, dan limbah elektronik.
                            Dengan memahami arti warna ini, kita dapat berkontribusi dalam menjaga kebersihan lingkungan serta mendukung sistem daur ulang yang lebih efektif.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-left">
                <!-- Judul dan Penjelasan Menggunakan Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex judul">
                        <h2 class="text-xl text-white bg-green-600 font-semibold p-4 inline-block rounded-xl">
                            Bank Sampah
                        </h2>
                    </div>

                    <!-- Gambar di Kanan -->
                    <div class="flex justify-center items-center">
                        <p class="text-lg hidden" style="text-align: justify;">
                        </p>
                    </div>
                </div>

                <!-- Gambar dan Penjelasan pada Mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Gambar di Kanan (di mobile) -->
                    <div class="text-left">
                        <p class="text-lg" style="text-align: justify;">
                        Bank sampah merupakan sistem pengelolaan sampah dengan mengumpulkan sampah milik warga untuk ditukar dengan uang.
                        </p>
                    </div>

                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex justify-center items-center hidden">
                        <img src="img/info3.png" alt="Ilustrasi Warna Tempat Sampah" class="img-bank">
                    </div>
                </div>

                <!-- Judul dan Penjelasan Menggunakan Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex judul">
                        <h2 class="text-xl text-white bg-green-600 font-semibold p-4 inline-block rounded-xl">
                            Cara Kerja Bank Sampah
                        </h2>
                    </div>

                    <!-- Gambar di Kanan -->
                    <div class="flex justify-center items-center">
                        <p class="text-lg hidden" style="text-align: justify;">
                        </p>
                    </div>
                </div>

                <!-- Gambar dan Penjelasan pada Mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Gambar di Kanan (di mobile) -->
                    <div class="text-left">
                        <p class="text-lg mb-2" style="text-align: justify;">
                            Berikut adalah langkah-langkah yang dilakukan oleh warga untuk berpartisipasi dalam program Bank Sampah:
                        </p>
                        <ol class="list-decimal pl-6 mb-4 text-black text-lg" style="text-align: justify; padding-left: 20px; list-style-type: decimal;">
                            <li>Warga mengumpulkan dan memilah sampah sesuai jenisnya.</li>
                            <li>Anggota/nasabah menyetor sampah ke Bank Sampah.</li>
                            <li>Sampah ditimbang oleh petugas, dengan minimal berat yang ditabung sebesar 1 kg.</li>
                            <li>Sampah dicatat sesuai berat dan jenisnya.</li>
                            <li>Petugas menghitung nilai sampah yang disetorkan, kemudian dicatat di buku tabungan anggota/nasabah.</li>
                        </ol>
                        <p class="text-lg mb-2" style="text-align: justify;">
                            Berikut adalah beberapa jenis sampah yang dapat Anda tabung di Bank Sampah:
                        </p>
                        <ol class="list-decimal pl-6 mb-4 text-black text-lg" style="text-align: justify; padding-left: 20px; list-style-type: decimal;">
                            <li>Kertas</li>
                            <li>Plastik</li>
                            <li>Logam</li>
                            <li>Kaca</li>
                        </ol>
                        <p class="text-lg" style="text-align: justify;">
                            Dengan mengikuti langkah-langkah tersebut, Anda turut berperan dalam menjaga kebersihan lingkungan sekaligus mendapatkan manfaat dari sampah yang dikelola dengan baik.
                        </p>
                    </div>

                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex justify-center items-center">
                        <img src="img/info3.png" alt="Ilustrasi Warna Tempat Sampah" class="img-bank">
                    </div>
                </div>

                <!-- Judul dan Penjelasan Menggunakan Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex judul">
                        <h2 class="text-xl text-white bg-green-600 font-semibold p-4 inline-block rounded-xl">
                            Saran
                        </h2>
                    </div>

                    <!-- Gambar di Kanan -->
                    <div class="flex justify-center items-center">
                        <p class="text-lg hidden" style="text-align: justify;">
                        </p>
                    </div>
                </div>

                <!-- Gambar dan Penjelasan pada Mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Gambar di Kanan (di mobile) -->
                    <div class="text-left">
                        <p class="text-lg" style="text-align: justify;">
                            Pastikan sampah dalam keadaan kering dan dimasukkan ke dalam Kantong putih/bakul/karung atau kantong dengan warna selain hitam.
                        </p>
                    </div>

                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex justify-center items-center hidden">
                        <img src="img/info3.png" alt="Ilustrasi Warna Tempat Sampah" class="img">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-right">
                <!-- Judul dan Penjelasan Menggunakan Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                    <!-- Gambar di Kanan -->
                    <div class="flex justify-center items-center">
                        <p class="text-lg hidden" style="text-align: justify;">
                            </p>
                        </div>
                        <!-- Judul dan Penjelasan di Kiri -->
                        <div class="flex judul">
                            <h2 class="text-xl text-white bg-green-600 font-semibold p-4 inline-block rounded-xl">
                                Dampak Buruk TPS Liar bagi Lingkungan
                            </h2>
                        </div>
                </div>

                <!-- Gambar dan Penjelasan pada Mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex justify-center items-center">
                        <img src="img/info4.png" alt="Ilustrasi Warna Tempat Sampah" class="img">
                    </div>

                    <!-- Gambar di Kanan (di mobile) -->
                    <div class="text-left">
                        <!-- Penjelasan -->
                        <p class="text-lg" style="text-align: justify;">
                        Tempat pembuangan sampah (TPS) liar masih menjadi masalah serius di banyak wilayah. Keberadaannya tidak hanya mengganggu estetika kota, tetapi juga mencemari tanah, air, dan udara. Sampah yang menumpuk tanpa pengelolaan memadai dapat memicu penyakit dan merusak ekosistem. Kesadaran masyarakat dan dukungan pemerintah sangat dibutuhkan untuk mengatasi persoalan ini secara berkelanjutan
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-left">
                <!-- Judul dan Penjelasan Menggunakan Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex judul">
                        <h2 class="text-xl text-white bg-green-600 font-semibold p-4 inline-block rounded-xl">
                            Peran Teknologi dalam Smart Environment
                        </h2>
                    </div>

                    <!-- Gambar di Kanan -->
                    <div class="flex justify-center items-center">
                        <p class="text-lg hidden" style="text-align: justify;">
                        </p>
                    </div>
                </div>

                <!-- Gambar dan Penjelasan pada Mobile -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Gambar di Kanan (di mobile) -->
                    <div class="text-left">
                        <p class="text-lg" style="text-align: justify;">
                        Teknologi memiliki peran besar dalam mendukung pengelolaan sampah yang lebih efisien dan transparan. Melalui aplikasi seperti CerdasSampah, masyarakat dapat melaporkan titik sampah liar, memantau jadwal pengangkutan, hingga mendapatkan informasi edukatif seputar sampah. Inovasi ini menjadi bagian dari transformasi menuju kota yang lebih bersih, cerdas, dan berkelanjutan.
                        </p>
                    </div>

                    <!-- Judul dan Penjelasan di Kiri -->
                    <div class="flex justify-center items-center">
                        <img src="img/info5.png" alt="Ilustrasi Warna Tempat Sampah" class="img">
                    </div>
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

    /* Mengatur warna teks untuk setiap span */
.text-green {
    color: #38A169; /* Warna hijau Tailwind */
    font-weight: 600; /* Untuk membuat teks tebal */
}

.text-yellow {
    color: #F59E0B; /* Warna kuning Tailwind */
    font-weight: 600; /* Untuk membuat teks tebal */
}

.text-red {
    color: #EF4444; /* Warna merah Tailwind */
    font-weight: 600; /* Untuk membuat teks tebal */
}

    .img {
    width: 95%;     /* Gambar akan mengisi lebar kontainer */
    height: 300px;   /* Anda bisa mengubah tinggi gambar sesuai keinginan */
    /* object-fit: cover;  Jika Anda ingin gambar menutupi kontainer dan terpotong jika perlu */
    display: block;  /* Pastikan gambar tetap berbentuk blok */
    margin: 0 auto;  /* Memastikan gambar berada di tengah */
    }

    .img-bank {
    width: 95%;     /* Gambar akan mengisi lebar kontainer */
    height: 400px;   /* Anda bisa mengubah tinggi gambar sesuai keinginan */
    /* object-fit: cover;  Jika Anda ingin gambar menutupi kontainer dan terpotong jika perlu */
    display: block;  /* Pastikan gambar tetap berbentuk blok */
    margin: 0 auto;  /* Memastikan gambar berada di tengah */
    }

    body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f9;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .header {
            background-color: #4CAF50;
            padding: 20px;
            color: white;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 48%;
            padding: 20px;
            margin-bottom: 20px;
        }

        .section h2 {
            color: #4CAF50;
        }

        .section p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .section img {
            width: 100%;
            max-width: 200px;
            margin-top: 20px;
            border-radius: 8px;
        }

        .section:last-child {
            margin-right: 0;
        }

        .section img {
            margin-left: auto;
            margin-right: auto;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }

            .section {
                width: 100%;
                margin-bottom: 20px;
            }

            .header {
                font-size: 18px;
            }

            .section img {
                max-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .header {
                font-size: 16px;
            }

            .section p {
                font-size: 14px;
            }

            .section img {
                max-width: 120px;
            }
        }
        /* Untuk tampilan mobile */
@media (max-width: 767px) {
    .judul {
        display: flex;
        justify-content: center;
    }
}

/* Untuk tampilan tablet */
@media (min-width: 768px) and (max-width: 1023px) {
    .judul {
        display: flex;
        justify-content: center;
    }
}

/* Untuk tampilan desktop */
@media (min-width: 1024px) {
    .judul {
        display: flex;
        justify-content: flex-start;
    }
}

  </style>

@endsection
