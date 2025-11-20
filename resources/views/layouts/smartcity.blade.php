<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartCity') - Menuju Kota Cerdas</title>

    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.8/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        window.addEventListener("scroll", function() {
            const header = document.getElementById("main-header");
            if (header) {
                if (window.scrollY > 100) {
                    header.classList.remove("bg-transparent");
                    header.classList.add("bg-blue-600", "shadow-md");
                } else {
                    header.classList.remove("bg-blue-600", "shadow-md");
                    header.classList.add("bg-transparent");
                }
            }
        });
    </script>

    {{-- <style>
        .swal2-confirm{background-color:#2563eb!important;color:#1b1414!important;}.swal2-confirm:hover{background-color:#1d4ed8!important;}
    </style> --}}
    @stack('styles')
</head>
<body class="font-sans overflow-x-hidden">
    @include('layouts.partials.smartcity_header')
    @yield('hero-background')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.smartcity_footer')

    {{-- Tombol Scroll-Up (biru) --}}
    <button id="scrollUpBtn" class="fixed bottom-4 right-4 bg-blue-600 text-white p-4 rounded-md shadow-lg hover:bg-orange-700 hover:text-white transition-opacity duration-300 opacity-0" style="z-index: 99999; opacity: 0; left: auto; bottom: 40px; right: 40px;">
        <i class="fa fa-arrow-up"></i>
    </button>

    {{-- Script Scroll-Up, SweetAlert, AOS --}}
    <script>
        const scrollUpBtn=document.getElementById("scrollUpBtn");window.addEventListener("scroll",function(){window.scrollY>100?scrollUpBtn.style.opacity="1":scrollUpBtn.style.opacity="0"}),scrollUpBtn.addEventListener("click",function(){window.scrollTo({top:0,behavior:"smooth"})});
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.8/dist/sweetalert2.min.js"></script>
    @if (session('success'))
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}' });</script>
    @endif
    @if (session('error'))
        <script>Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session('error') }}' });</script>
    @endif
    <script>AOS.init();</script>
    @stack('scripts')
</body>
</html>