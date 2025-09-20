<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'CerdasSampah.id')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS (output dari build lokal) -->
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">

    <!-- Library Tambahan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- AOS (Animate On Scroll) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- TypeIt -->
    <script src="https://cdn.jsdelivr.net/npm/typeit@8.7.1/dist/index.umd.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.8/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Sticky Header Scroll -->
    <script>
        window.addEventListener("scroll", function() {
            const header = document.getElementById("main-header");
            if (header) {
                if (window.scrollY > 100) {
                    header.classList.remove("bg-transparent");
                    header.classList.add("bg-green-500", "shadow-md");
                } else {
                    header.classList.remove("bg-green-500", "shadow-md");
                    header.classList.add("bg-transparent");
                }
            }
        });
    </script>
    <style>
        @keyframes smoothScroll {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-100px);
            }
        }

        /* Memperlambat animasi tombol muncul */
        .scrollUpBtn {
            animation: smoothScroll 1.5s ease-in-out;
        }

        /* Untuk durasi lebih lambat saat tombol scroll-up muncul */
        #scrollUpBtn {
            transition: opacity 0.6s ease;
        }

        /* Disable Scroll (untuk saat animasi berlangsung) */
        .no-scroll {
            overflow: hidden;
        }

        /* Menghapus semua outline, box-shadow, dan efek hover */
        .swal2-confirm {
            outline: none !important;
            /* Menghapus outline */
            box-shadow: none !important;
            /* Menghapus semua shadow */
            border: none !important;
            /* Menghapus border default */
        }

        /* Menghapus efek saat tombol terfokus */
        .swal2-confirm:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Menghapus efek saat tombol ditekan (active) */
        .swal2-confirm:active {
            box-shadow: none !important;
        }

        /* Menyesuaikan dengan warna tombol yang lebih bersih */
        .swal2-confirm {
            background-color: #4CAF50 !important;
            /* Warna hijau tombol */
            color: white !important;
            /* Warna teks putih */
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 20px;
        }

        /* Warna tombol saat hover */
        .swal2-confirm:hover {
            background-color: #45a049 !important;
            /* Warna hijau lebih gelap saat hover */
        }
    </style>

    @stack('styles')
</head>

<body class="bg-white text-gray-800 font-sans">

    @include('layouts.partials.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <!-- Tombol Scroll-Up -->
    <button id="scrollUpBtn"
        class="fixed bottom-4 right-4 bg-green-500 border-2 border-orange-500 text-white p-4 rounded-md shadow-lg hover:bg-orange-500 hover:text-white transition-opacity duration-300 opacity-0"
        style="z-index: 99999; opacity: 0; left: auto; bottom: 40px; right: 40px;">
        <!-- Ikon Panah ke Atas -->
        <i class="fa fa-arrow-up"></i>
    </button>

    <!-- JavaScript untuk Scroll dan Tombol Muncul -->
    <script>
        // Dapatkan elemen tombol
        const scrollUpBtn = document.getElementById("scrollUpBtn");

        // Menyimpan status animasi scroll (untuk memeriksa apakah scroll sedang berlangsung)
        let isScrolling = false;
        let scrollAnimationFrame;

        // Menambahkan event listener untuk scroll
        window.addEventListener("scroll", function() {
            // Jika animasi sedang berjalan, mencegah scroll ke bawah
            if (isScrolling) {
                window.scrollTo(0, window.scrollY); // Mengunci posisi scroll
            }

            // Tampilkan tombol ketika scroll lebih dari 100px
            if (window.scrollY > 100) {
                scrollUpBtn.style.opacity = "1"; // Menambahkan animasi tampilan
                scrollUpBtn.style.display = "block"; // Pastikan tombol terlihat
            } else {
                scrollUpBtn.style.opacity = "0"; // Sembunyikan tombol
                setTimeout(function() {
                    scrollUpBtn.style.display = "none"; // Sembunyikan tombol setelah animasi selesai
                }, 300); // Durasi waktu disesuaikan agar tombol hilang setelah animasi selesai
            }
        });

        // Menambahkan event listener untuk tombol scroll-up dengan durasi scroll lebih lama
        scrollUpBtn.addEventListener("click", function() {
            smoothScrollTo(0, 3000); // Scroll ke atas dalam 3 detik
        });

        // Fungsi untuk scroll lebih lambat
        function smoothScrollTo(targetY, duration) {
            // Menonaktifkan scroll selama animasi berlangsung
            document.body.classList.add("no-scroll");
            isScrolling = true;

            const startY = window.scrollY;
            const difference = targetY - startY;
            let startTime = null;

            function scrollStep(currentTime) {
                if (!startTime) startTime = currentTime;
                const progress = currentTime - startTime;
                const scroll = easeInOutQuad(progress, startY, difference, duration);
                window.scrollTo(0, scroll);
                if (progress < duration) {
                    scrollAnimationFrame = requestAnimationFrame(scrollStep);
                } else {
                    // Mengaktifkan kembali scroll setelah animasi selesai
                    document.body.classList.remove("no-scroll");
                    isScrolling = false;
                }
            }

            function easeInOutQuad(t, b, c, d) {
                t /= d / 2;
                if (t < 1) return (c / 2) * t * t + b;
                t--;
                return (-c / 2) * (t * (t - 2) - 1) + b;
            }

            scrollAnimationFrame = requestAnimationFrame(scrollStep);
        }

        // Menambahkan event listener untuk membatalkan animasi jika pengguna scroll ke bawah
        window.addEventListener("wheel", function(event) {
            if (isScrolling && event.deltaY > 0) {
                // Jika animasi sedang berjalan dan pengguna menggulir ke bawah
                // Batalkan animasi scroll
                cancelAnimationFrame(scrollAnimationFrame); // Hentikan animasi
                isScrolling = false;
                document.body.classList.remove("no-scroll");
                window.scrollTo(0, window.scrollY); // Mengunci posisi
            }
        });

        window.addEventListener("touchmove", function(event) {
            if (isScrolling) {
                // Jika animasi sedang berjalan, batalkan
                event.preventDefault(); // Menangani scroll pada perangkat sentuh (mobile)
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.8/dist/sweetalert2.min.js"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Laporan Berhasil Dikirim',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn-custom' // Kelas khusus untuk tombol
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                {{-- title: 'Laporan Gagal Dikirim', --}}
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn-custom' // Kelas khusus untuk tombol
                }
            });
            {{-- <div class="alert alert-danger" role="alert">
    {{ session('error') }}
</div> --}}
        </script>
    @endif

    <script>
        AOS.init();
    </script>
    @stack('scripts')
</body>

</html>
