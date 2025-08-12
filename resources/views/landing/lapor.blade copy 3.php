@extends('layouts.app')

@section('content')

<div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] z-[-999]">
    <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 490" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150">
        <path d="M 0,500 L 0,187 C 257.5,150.5 515,114 755,114 C 995,114 1217.5,150.5 1440,187 L 1440,500 L 0,500 Z" stroke="none" stroke-width="0" fill="#60cb83" fill-opacity="1" class="transition-all duration-300 ease-in-out delay-150 path-0" transform="rotate(-180 720 250)"></path>
    </svg>
</div>

<div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 py-20 lg:py-32 flex flex-col-reverse lg:flex-row items-center justify-between gap-10 overflow-hidden" style="padding-bottom: 48px;">
    <!-- Konten Kiri -->
    <div class="w-full text-center lg:text-left">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mt-6 mb-12 text-white" data-aos="zoom-in">
            Laporan TPS Liar
        </h1>

        <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-up">
            <!-- Form Laporan -->
            <form action="#" method="POST">
                @csrf

                <!-- Nama Lengkap dan Email -->
                <div class="flex flex-col lg:flex-row gap-6 mb-6">
                    <div class="w-full lg:w-1/2">
                        <label for="name" class="block text-lg font-semibold text-gray-600">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Nama" required>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <label for="email" class="block text-lg font-semibold text-gray-600">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Email" required>
                    </div>
                </div>

                <!-- Peta (Leaflet Map) -->
                <div class="mb-6">
                    <label for="map" class="block text-lg font-semibold text-gray-600">Peta</label>
                    <div id="mapid" class="w-full h-52 bg-gray-100 rounded-lg mb-4">
                        <!-- Leaflet Map will be initialized here -->
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <label for="address" class="block text-lg font-semibold text-gray-600">Alamat</label>
                    <input type="text" id="address" name="address" class="w-full px-4 py-3 border rounded-lg mt-2 focus:ring-2 focus:ring-green-500" placeholder="Alamat" required>
                </div>

                <!-- Unggah Bukti Pendukung -->
                <div class="mb-6">
                    <label for="file" class="block text-lg font-semibold text-gray-600">Unggah Bukti Pendukung</label>
                    <div class="flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg mt-2">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="33" viewBox="0 0 50 33" fill="none">
                                <path d="M47.1056 15.5849C43.9543 12.3535 39.9359 9.09895 37.0498 5.62873C36.4114 4.86114 35.6959 4.15187 34.9106 3.51294C32.1178 1.24087 28.6148 -0.000809 25 3.95457e-07C20.9023 3.95457e-07 17.1713 1.56676 14.385 4.12812C11.931 6.38397 8.99308 8.30236 6.11843 9.9898C5.03637 10.625 4.04884 11.4234 3.19477 12.3653C1.13744 14.6343 -0.000357712 17.576 8.43599e-08 20.625C8.43599e-08 27.4519 5.60417 33 12.5 33H39.5833C45.3333 33 50 28.38 50 22.6875C50 19.9226 48.8988 17.4237 47.1056 15.5849ZM39.5833 28.875H12.5C7.89583 28.875 4.16667 25.1831 4.16667 20.625C4.16667 16.3969 7.35417 12.87 11.5833 12.4369C12.963 12.2965 14.2032 11.4751 14.8542 10.2506C15.8124 8.40438 17.2666 6.85565 19.0567 5.77488C20.8468 4.69411 22.9033 4.12321 25 4.125C30.4583 4.125 35.1667 7.96125 36.2292 13.2619C36.5985 15.0901 38.142 16.4473 40.0025 16.5797L40.0417 16.5825C41.6078 16.6868 43.0759 17.3745 44.1505 18.5072C45.225 19.6399 45.8263 21.1336 45.8333 22.6875C45.8333 26.0906 43.0208 28.875 39.5833 28.875ZM19.3418 15.9141C18.3589 16.8871 19.048 18.5625 20.431 18.5625C21.286 18.5625 21.9792 19.2556 21.9792 20.1107V21.7292C21.9792 23.3975 23.3316 24.75 25 24.75C26.6684 24.75 28.0208 23.3975 28.0208 21.7292V20.1107C28.0208 19.2556 28.714 18.5625 29.569 18.5625C30.952 18.5625 31.6411 16.8871 30.6582 15.9141L27.4112 12.6996C26.0756 11.3774 23.9244 11.3774 22.5888 12.6996L19.3418 15.9141Z" fill="#757575"/>
                            </svg>
                            <input type="file" id="file" name="file" class="">
                        </div>
                    </div>
                </div>

                <!-- Tombol Kirim Laporan -->
                <div class="mt-6">
                    <button type="submit" class="w-full py-3 text-white text-lg bg-green-600 font-semibold rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>

    </div>

@endsection

<style>
    /* Flexbox for Form Elements */
    .flex {
        display: flex;
        gap: 20px;
    }

    .flex-col-reverse {
        flex-direction: column-reverse;
    }

    /* For mobile responsiveness */
    @media (max-width: 768px) {
        .flex {
            flex-direction: column;
        }
    }

    .img {
        width: 95%;
        height: 300px;
        display: block;
        margin: 0 auto;
    }
</style>
