@extends('layouts.dashboard')

@section('title', 'Home')

@section('content')

    {{-- Hero Section dengan Latar Bergelombang --}}
    <div class="relative bg-gradient-to-b from-green-700 via-green-700 to-green-500 -mt-4 sm:-mt-6 text-white mb-6">

        <div class="relative z-10 p-8 pt-10 pb-20 flex flex-col justify-center items-center text-center">
            <div class="flex items-center justify-center w-24 h-24 mb-4 bg-white rounded-full overflow-hidden shadow-md">
                @if (Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-20 h-20 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                @endif
            </div>
            <h2 class="text-4xl font-bold">Halo, {{ Auth::user()->username }}!</h2>
            <p class="mt-1 text-lg text-white/90">Sudah buang sampah hari ini?</p>
        </div>
        <div class="flex items-center justify-center w-8 h-8 mb-4 rounded-full overflow-hidden">
        </div>

        <div class="absolute bottom-[-1px] left-0 w-full text-slate-50">
            <svg viewBox="0 0 1440 120" fill="currentColor" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M1440,120H0V20.48c0,0,202.4,69.52,480,69.52s480-139.04,960-69.52V120Z"></path>
            </svg>
        </div>
    </div>

    {{-- KONTEN KARTU --}}
    <div class="p-4 sm:p-6 lg:p-8 -mt-16 relative z-10 ">
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
            {{-- Kartu Misi dengan Ikon Award Baru --}}
            <div class="flex items-center justify-between p-6 bg-amber-400 rounded-2xl shadow-lg">
                <div>
                    <p class="font-semibold text-amber-900 text-lg">Progres Misi Harian</p>
                    <p class="text-sm text-amber-800">{{ $completedQuests }} dari {{ $totalQuests }} misi selesai</p>
                </div>

                <div class="flex items-center justify-center">
                    <svg class="w-12 h-12 text-white opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center justify-between p-6 bg-amber-400 rounded-2xl shadow-lg">
                <div>
                    <p class="text-xl font-semibold text-amber-900">{{ number_format($userPoints, 0, ',', '.') }} points</p>
                    <p class="text-sm text-amber-800">Ayo terus kumpulkan poinmu!</p>
                </div>
                <div class="flex items-center justify-center">
                    <svg class="w-12 h-12 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 1.27l2.82 5.72 6.32.92-4.57 4.45 1.08 6.3L10 15.5l-5.65 2.97 1.08-6.3-4.57-4.45 6.32-.92z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="relative bg-green-200 p-6 rounded-2xl shadow-lg overflow-hidden mt-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6 relative z-10">Misi Harian</h2>
            <div class="absolute -top-12 left-10 opacity-100 text-white pointer-events-none transform rotate-12">
                <svg class="w-[350px] h-[200px]" viewBox="0 0 501 501" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.6"
                        d="M0.553223 500.555H111.664V444.999H389.442V500.555H500.553V222.777H444.998V167.221H278.331V56.1102H222.775V167.221H56.1088L56.1088 222.777H0.553223L0.553223 500.555ZM111.664 389.444L111.664 333.888H56.1088L56.1088 278.332H111.664V222.777H167.22L167.22 278.332H222.775V333.888H167.22V389.444H111.664ZM333.887 389.444V333.888H278.331V278.332H333.887V222.777H389.442V278.332H444.998V333.888H389.442V389.444H333.887ZM333.887 333.888H389.442V278.332H333.887V333.888ZM167.22 56.1102L222.775 56.1102V0.554687L167.22 0.554687V56.1102Z" />
                </svg>
            </div>
            <div class="absolute -bottom-20 -right-10 opacity-100 text-white pointer-events-none transform rotate-12">
                <svg class="w-[500px] h-auto" viewBox="0 0 501 501" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.6"
                        d="M0.553223 500.555H111.664V444.999H389.442V500.555H500.553V222.777H444.998V167.221H278.331V56.1102H222.775V167.221H56.1088L56.1088 222.777H0.553223L0.553223 500.555ZM111.664 389.444L111.664 333.888H56.1088L56.1088 278.332H111.664V222.777H167.22L167.22 278.332H222.775V333.888H167.22V389.444H111.664ZM333.887 389.444V333.888H278.331V278.332H333.887V222.777H389.442V278.332H444.998V333.888H389.442V389.444H333.887ZM333.887 333.888H389.442V278.332H333.887V333.888ZM167.22 56.1102L222.775 56.1102V0.554687L167.22 0.554687V56.1102Z" />
                </svg>
            </div>
            <div class="relative z-10 grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($dailyQuests as $userQuest)
                    @php
                        $isCompleted = $userQuest->is_completed;
                        $cardClasses = $isCompleted ? 'bg-white' : 'bg-green-700 text-white';
                        $iconWrapperClasses = $isCompleted ? 'bg-gray-100' : 'bg-white/20';
                        $iconClasses = $isCompleted ? 'text-gray-400' : 'text-white';
                        $titleClasses = $isCompleted ? 'text-gray-500' : 'text-white';
                    @endphp

                    <div
                        class="p-6 text-center rounded-2xl shadow-lg flex flex-col justify-between transition-all duration-300 {{ $cardClasses }}">
                        <div>
                            <div class="inline-block p-5 mb-4 rounded-full {{ $iconWrapperClasses }}">
                                @if ($userQuest->quest->quest_type == 'scan')
                                    <svg class="w-10 h-10 {{ $iconClasses }}" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                @else
                                    <svg class="w-10 h-10 {{ $iconClasses }}" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.79 4 4s-1.79 4-4 4c-1.742 0-3.223-.835-3.772-2M12 5v.01M12 19v.01M4.2 10.225l-.01.01M19.8 15.775l-.01.01M4.2 15.775l.01.01M19.8 10.225l.01.01">
                                        </path>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="font-semibold {{ $titleClasses }}">{{ $userQuest->quest->quest_name }}</h3>
                        </div>
                        <div class="mt-4">
                            @if ($isCompleted)
                                <button
                                    class="w-full py-2.5 font-semibold text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed"
                                    disabled>
                                    Selesai
                                </button>
                            @else
                                @php
                                    $route = '#';
                                    if ($userQuest->quest->quest_type == 'scan') {
                                        $route = route('scan-user');
                                    } elseif ($userQuest->quest->quest_type == 'game') {
                                        $route = route('game-pilah-sampah');
                                    }
                                @endphp
                                <a href="{{ $route }}"
                                    class="block w-full py-2.5 font-semibold text-green-800 bg-green-100 hover:bg-green-200 transition rounded-lg">
                                    Kerjakan Misi
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 col-span-full bg-white rounded-2xl shadow-lg">
                        <p>Tidak ada misi untuk hari ini. Coba kembali besok!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-8 bg-amber-400 p-6 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold text-center text-amber-900 mb-6">Setorkan sampahmu</h2>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Kartu: Dapat Poin & Reward --}}
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    {{-- Ikon diubah gayanya agar memiliki circle background --}}
                    <div class="inline-block p-5 mb-4 bg-amber-100 rounded-full">
                        <svg class="w-10 h-10 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-6.75c-.621 0-1.125.504-1.125 1.125V18.75m9 0h-9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 21.75h4.5M12 7.5v9M9.75 7.5a2.25 2.25 0 012.25-2.25h0a2.25 2.25 0 012.25 2.25v0a2.25 2.25 0 01-2.25 2.25h-2.25a2.25 2.25 0 01-2.25-2.25v0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 mb-1">Dapat Poin & Reward</h3>
                    <p class="text-sm text-gray-600">Setorkan sampahmu untuk dapat poin yang bisa ditukarkan dengan berbagai hadiah menarik.</p>
                </div>

                {{-- Kartu: Ikut Menjaga Lingkungan --}}
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    {{-- Ikon diubah gayanya agar memiliki circle background --}}
                    <div class="inline-block p-5 mb-4 bg-amber-100 rounded-full">
                        <svg class="w-10 h-10 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 mb-1">Ikut Menjaga Lingkungan</h3>
                    <p class="text-sm text-gray-600">Setiap sampah yang kamu setorkan sangat berarti untuk membantu mengurangi pencemaran lingkungan.</p>
                </div>

                {{-- Kartu: Memberi Nilai Tambah --}}
                <div class="bg-white p-6 rounded-2xl shadow-md text-center">
                    {{-- Ikon diubah gayanya agar memiliki circle background --}}
                    <div class="inline-block p-5 mb-4 bg-amber-100 rounded-full">
                        <svg class="w-10 h-10 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 7.756a4.5 4.5 0 100 8.488M7.5 10.5h5.25m-5.25 3h5.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 mb-1">Memberi Nilai Tambah</h3>
                    <p class="text-sm text-gray-600">Ubah sampah tak terpakai menjadi barang bernilai ekonomis sambil mendukung gerakan daur ulang.</p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('banksampah-user') }}" class="inline-block bg-white text-amber-800 font-semibold px-10 py-3 rounded-lg border border-amber-300 hover:bg-amber-50 transition shadow-sm">
                    Setorkan sampah
                </a>
            </div>
        </div>

        <div class="relative bg-green-200 p-8 rounded-2xl shadow-lg overflow-hidden mt-8">
            <div class="absolute -bottom-50 right-80 opacity-50 text-white pointer-events-none ">
                <svg class="w-[350px] h-[497px]" viewBox="0 0 400 461" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M181.25 0C175.107 1.37772 169.131 3.41431 163.425 6.07439C63.825 52.6197 0 182.782 0 294.295C0 400.285 79.575 487.376 181.25 497V0ZM218.75 497C320.425 487.376 400 400.31 400 294.32C400 284.155 399.475 273.872 398.425 263.474L218.75 443.155V497ZM356.5 127.412C346.908 109.43 335.695 92.3612 323 76.4173L218.75 180.657V265.148L356.5 127.412ZM297.7 48.6701C279.776 31.2442 259.13 16.8569 236.575 6.07439C230.869 3.41431 224.893 1.37772 218.75 0V127.637L297.7 48.6701ZM373.4 163.509L218.75 318.168V390.111L387.5 221.378L390.8 218.078C386.4 199.471 380.583 181.228 373.4 163.509Z" />
                </svg>
            </div>
            <div class="absolute -right-20 -bottom-20 opacity-50 text-white pointer-events-none">
                <svg class="w-[400px] h-[461px]" viewBox="0 0 400 461" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M181.25 0C175.107 1.37772 169.131 3.41431 163.425 6.07439C63.825 52.6197 0 182.782 0 294.295C0 400.285 79.575 487.376 181.25 497V0ZM218.75 497C320.425 487.376 400 400.31 400 294.32C400 284.155 399.475 273.872 398.425 263.474L218.75 443.155V497ZM356.5 127.412C346.908 109.43 335.695 92.3612 323 76.4173L218.75 180.657V265.148L356.5 127.412ZM297.7 48.6701C279.776 31.2442 259.13 16.8569 236.575 6.07439C230.869 3.41431 224.893 1.37772 218.75 0V127.637L297.7 48.6701ZM373.4 163.509L218.75 318.168V390.111L387.5 221.378L390.8 218.078C386.4 199.471 380.583 181.228 373.4 163.509Z" />
                </svg>
            </div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="text-center lg:text-left">
                    <span class="inline-block bg-green-700 text-white px-3 py-1 rounded-md text-3xl font-semibold">Mau
                        Daur
                        Ulang ?</span>
                    <h2 class="text-3xl lg:text-3xl font-extrabold text-gray-800 mt-4 leading-tight">Yuk, ubah sampah jadi
                        cuan di Cerdas Sampah!</h2>
                    <p class="mt-4 text-gray-600">Ubah sampah terpilahmu jadi peluang! Jual kardus, botol plastik, hingga
                        minyak jelantah langsung ke pengepul atau UMKM daur ulang.</p>
                    <a href="#"
                        class="inline-block mt-6 bg-white text-gray-800 font-bold py-3 px-6 rounded-lg shadow-md hover:bg-gray-100 transition-colors duration-200">
                        Jelajahi Marketplace!
                    </a>
                </div>
                <div class="flex justify-center items-center">
                    <div
                        class="w-48 h-48 lg:w-60 lg:h-60 bg-white rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-24 h-24 lg:w-32 lg:h-32" viewBox="0 0 177 126" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M88.0923 27.7839C71.8298 11.5089 51.5048 4.69642 35.6173 1.90892C29.0124 0.740007 22.3241 0.104623 15.6173 0.00891737C13.0825 -0.024936 10.5473 0.0376075 8.01727 0.196416L7.55476 0.233916H7.42976L7.37977 0.246418H7.35476L2.60476 0.696418L1.79226 5.39642L1.77976 5.45892L1.75476 5.60892L1.65476 6.17142L1.36726 8.20892C0.017734 18.7062 -0.333838 29.3078 0.317264 39.8714C1.54226 58.7589 6.66726 82.7964 23.3673 99.4964C39.6923 115.821 59.5923 122.184 75.1173 124.509C81.8383 125.517 88.637 125.915 95.4298 125.696V115.321L47.2673 67.1339L56.1173 58.2839L95.4423 97.6339V36.7964C93.2923 33.5464 90.8423 30.5339 88.0923 27.7839ZM107.942 53.9714V99.7214L129.042 82.8464L136.842 92.5964L107.942 115.721V125.571C112.513 125.626 117.082 125.367 121.617 124.796C132.98 123.334 147.742 119.271 158.717 108.296C169.792 97.2089 174.055 80.4714 175.742 67.5589C176.736 59.7761 177.063 51.9224 176.717 44.0839L176.692 43.6839V43.5589L176.68 43.5339L176.305 38.4964L171.33 37.7839H171.28L171.167 37.7589L170.792 37.7089L169.405 37.5464C162.419 36.8394 155.385 36.7224 148.38 37.1964C136.167 38.0964 120.067 41.3339 109.692 52.1464L109.192 52.6464L107.942 53.9714Z"
                                fill="#5E936C" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
