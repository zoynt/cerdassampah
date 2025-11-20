@extends('layouts.smartcity')

@section('content')

    {{-- Latar belakang gelombang biru --}}
    <div class="relative bg-gradient-to-b from-blue-700 via-blue-700 to-blue-500 -mt-4 sm:-mt-6 text-white mb-6">

        <div class="relative z-10 p-8 pt-10 pb-20 flex flex-col justify-center items-center text-center">
            <div class="flex items-center justify-center w-24 h-24 mb-8 rounded-full overflow-hidden">
            </div>
            <h1 class="text-2xl md:text-4xl font-bold leading-tight mb-16 text-white" data-aos="zoom-in">
                Informasi Smart City
            </h1>
        </div>

        <div class="absolute bottom-[-1px] left-0 w-full text-white">
            <svg viewBox="0 0 1440 120" fill="currentColor" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M1440,120H0V20.48c0,0,202.4,69.52,480,69.52s480-139.04,960-69.52V120Z"></path>
            </svg>
        </div>
    </div>

    {{-- Konten Halaman --}}
    <div
        class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 pb-16 lg:pb-32 flex flex-col items-center justify-between gap-10 overflow-hidden">

        <div class="w-full text-center">
            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-left">
                <div class="flex justify-center lg:justify-start">
                    <h2 class="text-base md:text-xl text-white bg-blue-600 font-semibold p-4 inline-block rounded-xl mb-6">
                        Transformasi Digital dalam Layanan Publik
                    </h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                    <div class="text-left">
                        <p class="text-sm md:text-lg text-justify text-gray-700">
                            Transformasi digital telah menjadi tulang punggung dalam pengembangan smart city. Dengan
                            memanfaatkan teknologi seperti sistem informasi terintegrasi, aplikasi layanan publik, dan data
                            real-time, pemerintah daerah dapat memberikan pelayanan yang lebih cepat, transparan, dan
                            efisien. Hal ini tidak hanya mempercepat proses birokrasi, tetapi juga mendorong keterlibatan
                            masyarakat dalam pengawasan dan evaluasi layanan.
                        </p>
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="{{ asset('img/content1.png') }}" alt="Ilustrasi Smart Governance"
                            class="w-full max-w-md h-auto rounded-lg object-cover">
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-right">
                <div class="flex justify-center lg:justify-end">
                    <h2 class="text-base md:text-xl text-white bg-blue-600 font-semibold p-4 inline-block rounded-xl mb-6">
                        Peran Masyarakat dalam Ekosistem Kota Cerdas
                    </h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                    <div class="flex justify-center items-center row-start-2 lg:row-start-1">
                        <img src="{{ asset('img/content2.png') }}" alt="Ilustrasi Smart Branding"
                            class="w-full max-w-md h-auto rounded-lg object-cover">
                    </div>
                    <div class="text-left row-start-1 lg:row-start-1">
                        <p class="text-sm md:text-lg text-justify text-gray-700">
                            Kesuksesan smart city tidak hanya bergantung pada teknologi, tetapi juga pada keterlibatan aktif
                            masyarakatnya. Warga kota cerdas didorong untuk menjadi bagian dari solusi, misalnya dengan
                            melaporkan masalah lingkungan melalui aplikasi, berpartisipasi dalam forum digital, atau
                            menggunakan layanan publik secara bijak. Kolaborasi antara pemerintah, swasta, dan masyarakat
                            menciptakan ekosistem kota yang adaptif dan berkelanjutan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-left">
                <div class="flex justify-center lg:justify-start">
                    <h2 class="text-base md:text-xl text-white bg-blue-600 font-semibold p-4 inline-block rounded-xl mb-6">
                        Keberlanjutan dan Kota Ramah Lingkungan
                    </h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                    <div class="text-left">
                        <p class="text-sm md:text-lg text-justify text-gray-700">
                            Salah satu tujuan utama smart city adalah menciptakan kota yang berkelanjutan. Hal ini tercermin
                            dari upaya pengurangan emisi, pengelolaan sampah berbasis teknologi, serta pengembangan ruang
                            hijau yang terintegrasi. Dengan pendekatan ini, kota tidak hanya menjadi lebih modern, tetapi
                            juga lebih sehat, aman, dan nyaman untuk dihuni oleh generasi sekarang dan mendatang.
                        </p>
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="{{ asset('img/content3.png') }}" alt="Ilustrasi Smart Economy"
                            class="w-full max-w-md h-auto rounded-lg object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
