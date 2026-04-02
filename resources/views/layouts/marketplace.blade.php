<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marketplace') - CerdasSampah</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    {{-- CSS global layout dari Vite (Ini sudah benar) --}}
    @vite('resources/css/app.css')

    {{-- Fonts & Alpine --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- [TAMBAHKAN] Library Tambahan dari layout landing --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.8/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- Script Sticky Header (Ini sudah ada sebelumnya) --}}
    <script>
        window.addEventListener("scroll", function () {
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
    
    {{-- Style untuk SweetAlert & Scroll (Ini sudah ada sebelumnya) --}}
    <style>
        @keyframes smoothScroll{0%{transform:translateY(0)}100%{transform:translateY(-100px)}}.scrollUpBtn{animation:smoothScroll 1.5s ease-in-out}#scrollUpBtn{transition:opacity .6s ease}.no-scroll{overflow:hidden}.swal2-confirm{outline:none!important;box-shadow:none!important;border:none!important}.swal2-confirm:focus{outline:none!important;box-shadow:none!important}.swal2-confirm:active{box-shadow:none!important}.swal2-confirm{background-color:#4CAF50!important;color:#fff!important;font-size:16px;font-weight:700;border-radius:5px;padding:10px 20px}.swal2-confirm:hover{background-color:#45a049!important}
    </style>

    @stack('head')
    @stack('styles')
</head>
<body class="text-gray-800 bg-gray-100 font-sans">

    {{-- Memanggil Navbar --}}
    @include('layouts.partials.header')
    @yield('hero-background')

    <main class="pt-20 pb-16">
        @yield('content')
    </main>

    {{-- Memanggil Footer --}}
    @include('layouts.partials.footer')
    
    {{-- [TAMBAHKAN] Tombol Scroll-Up & Scriptnya dari layout landing --}}
    <button id="scrollUpBtn" class="fixed bottom-4 right-4 bg-green-500 border-2 text-white p-4 rounded-md shadow-lg hover:bg-orange-500 hover:text-white transition-opacity duration-300 opacity-0" style="z-index: 99999; opacity: 0; left: auto; bottom: 40px; right: 40px;">
        <i class="fa fa-arrow-up"></i>
    </button>

    <script>
        const scrollUpBtn=document.getElementById("scrollUpBtn");let isScrolling=!1,scrollAnimationFrame;function smoothScrollTo(e,t){document.body.classList.add("no-scroll"),isScrolling=!0;const o=window.scrollY,n=e-o;let l=null;function c(e){l||(l=e);const a=e-l,i=function(e,t,o,n){return(e/=n/2)<1?o/2*e*e+t:(-o/2)*(--e*(e-2)-1)+t}(a,o,n,t);window.scrollTo(0,i),a<t?scrollAnimationFrame=requestAnimationFrame(c):(document.body.classList.remove("no-scroll"),isScrolling=!1)}scrollAnimationFrame=requestAnimationFrame(c)}window.addEventListener("scroll",function(){isScrolling?window.scrollTo(0,window.scrollY):window.scrollY>100?(scrollUpBtn.style.opacity="1",scrollUpBtn.style.display="block"):(scrollUpBtn.style.opacity="0",setTimeout(function(){scrollUpBtn.style.display="none"},300))}),scrollUpBtn.addEventListener("click",function(){smoothScrollTo(0,3e3)}),window.addEventListener("wheel",function(e){isScrolling&&e.deltaY>0&&(cancelAnimationFrame(scrollAnimationFrame),isScrolling=!1,document.body.classList.remove("no-scroll"),window.scrollTo(0,window.scrollY))}),window.addEventListener("touchmove",function(e){isScrolling&&e.preventDefault()});
    </script>
    
    {{-- [TAMBAHKAN] Script SweetAlert & AOS init dari layout landing --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.8/dist/sweetalert2.min.js"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <script>AOS.init();</script>

    {{-- Tempat untuk script khusus halaman --}}
    @stack('scripts')
</body>

</html>