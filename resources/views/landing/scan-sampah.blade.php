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
            Scan Sampah
        </h1>

        <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-up">
            <!-- Form Laporan -->
            <form id="scan-form" action="{{ route('scan.scan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Unggah Foto Sampah -->
                <div class="mb-6 flex flex-col items-center sm:items-start">
                    <label for="file" class="block text-lg font-semibold text-gray-600">Unggah Foto Sampah</label>
                    <div class="flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg mt-2 cursor-pointer" id="upload-area" onclick="document.getElementById('file').click()">
                        <div class="flex flex-col items-center justify-center w-full text-center" id="upload-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="33" viewBox="0 0 50 33" fill="none" id="upload-icon">
                                <path d="M47.1056 15.5849C43.9543 12.3535 39.9359 9.09895 37.0498 5.62873C36.4114 4.86114 35.6959 4.15187 34.9106 3.51294C32.1178 1.24087 28.6148 -0.000809 25 3.95457e-07C20.9023 3.95457e-07 17.1713 1.56676 14.385 4.12812C11.931 6.38397 8.99308 8.30236 6.11843 9.9898C5.03637 10.625 4.04884 11.4234 3.19477 12.3653C1.13744 14.6343 -0.000357712 17.576 8.43599e-08 20.625C8.43599e-08 27.4519 5.60417 33 12.5 33H39.5833C45.3333 33 50 28.38 50 22.6875C50 19.9226 48.8988 17.4237 47.1056 15.5849ZM39.5833 28.875H12.5C7.89583 28.875 4.16667 25.1831 4.16667 20.625C4.16667 16.3969 7.35417 12.87 11.5833 12.4369C12.963 12.2965 14.2032 11.4751 14.8542 10.2506C15.8124 8.40438 17.2666 6.85565 19.0567 5.77488C20.8468 4.69411 22.9033 4.12321 25 4.125C30.4583 4.125 35.1667 7.96125 36.2292 13.2619C36.5985 15.0901 38.142 16.4473 40.0025 16.5797L40.0417 16.5825C41.6078 16.6868 43.0759 17.3745 44.1505 18.5072C45.225 19.6399 45.8263 21.1336 45.8333 22.6875C45.8333 26.0906 43.0208 28.875 39.5833 28.875ZM19.3418 15.9141C18.3589 16.8871 19.048 18.5625 20.431 18.5625C21.286 18.5625 21.9792 19.2556 21.9792 20.1107V21.7292C21.9792 23.3975 23.3316 24.75 25 24.75C26.6684 24.75 28.0208 23.3975 28.0208 21.7292V20.1107C28.0208 19.2556 28.714 18.5625 29.569 18.5625C30.952 18.5625 31.6411 16.8871 30.6582 15.9141L27.4112 12.6996C26.0756 11.3774 23.9244 11.3774 22.5888 12.6996L19.3418 15.9141Z" fill="#757575"/>
                            </svg>
                            <span class="text-lg text-gray-500" id="upload-text">Unggah atau ambil foto di sini</span>
                            <div id="file-name" class="mt-2 text-gray-600 hidden"></div>
                            <img id="uploaded-image" src="" alt="" class="hidden mt-2 max-w-full h-auto rounded-lg">
                        </div>
                    </div>

                    <input type="file" id="file" name="file" class="hidden" accept=".png, .jpg, .jpeg" onchange="displayFileName()">
                </div>

                <div class="mt-6">
                    <button id="scan-sampah-btn" type="submit" class="w-full py-3 text-white text-lg bg-green-600 font-semibold rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                        Scan Sampah
                    </button>
                </div>
            </form>
            </div>

        </div>

        <!-- Card Hasil Scan Sampah -->
        <div id="scan-result" class="bg-white rounded-xl shadow-md mt-8 p-8 hidden">
            <h2 class="text-2xl font-bold text-center mb-4">Hasil Scan</h2>
            <div class="flex items-center justify-between">
                <img id="result-image" src="" alt="Hasil Scan" class="w-24 h-24 object-cover rounded-lg mr-4">
                <div class="ml-4 flex flex-col">
                    <h3 id="scan-title" class="font-bold text-xl"></h3>
                    <p id="scan-type" class="text-gray-600 text-sm"></p>
                </div>
            </div>
            <div class="mt-6">
                <h4 class="font-bold">Saran Penanganan:</h4>
                <ul id="handling-tips" class="list-disc pl-5 text-gray-600"></ul>
            </div>
            <div class="mt-6">
                <h4 class="font-bold">Daur Ulang:</h4>
                <ul id="recycling-tips" class="list-disc pl-5 text-gray-600"></ul>
            </div>
        </div>

        <!-- Popup Langkah -->
        <div id="steps-popup" class="steps-popup-overlay" style="display: none;">
            <div class="steps-popup">
                <h2 class="steps-popup-title mb-6">Langkah Mudah Menggunakan Fitur Scan</h2>
                <div class="pagination-navigation mb-2">
                    <span id="prev-step" class="pagination-arrow">&#60;</span>
                    <div class="pagination-numbers">
                        <span id="page-1" class="pagination-number active">1</span>
                        <span id="page-2" class="pagination-number">2</span>
                        <span id="page-3" class="pagination-number">3</span>
                    </div>
                    <span id="next-step" class="pagination-arrow">&#62;</span>
                </div>

                <div class="steps-popup-content">
                    <div class="steps-popup-step active">
                        <h3 class="mb-2 font-bold">Unggah Gambar Sampah</h3>
                        <p class="mb-4">Ambil foto sampah menggunakan kamera langsung dari aplikasi atau unggah gambar dari galeri.</p>
                    </div>
                    <div class="steps-popup-step">
                        <h3 class="mb-2 font-bold">Sistem Menganalisis Gambar</h3>
                        <p class="mb-4">Sistem kami akan memproses gambar dan mengenali jenis sampah berdasarkan pola visual.</p>
                    </div>
                    <div class="steps-popup-step">
                        <h3 class="mb-2 font-bold">Hasil Analisis Ditampilkan</h3>
                        <p class="mb-4">Hasil klasifikasi akan muncul secara otomatis (Organik, anorganik dan berbahaya B3)</p>
                    </div>
                </div>

                <button id="close-popup" class="btn btn-secondary">Tutup</button>
            </div>
        </div>


        <style>
            html, body {
                overflow-x: hidden;
            }
            /* --- Popup --- */
            .steps-popup-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }
            .steps-popup {
                background-color: #fff;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                text-align: center;
                width: 80%;
                max-width: 450px;
                overflow: hidden;
            }
            /* --- Styling Upload Area --- */
            #upload-area {
                display: flex;
                align-items: center;
                justify-content: center;
                border: 2px dashed #D1D5DB;
                border-radius: 10px;
                transition: border-color 0.3s ease-in-out;
                overflow: hidden;
                height: 300px;
            }
            #upload-area.file-selected {
                border-color: #38A169; /* Hijau */
            }
            #uploaded-image {
                display: block;
                width: 100%;
                height: auto;
                border-radius: 10px;
            }
            /* --- Scan Result --- */
            #scan-result {
                display: none;
                background-color: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin-top: 20px;
            }
        </style>

        <script>
            AOS.init();
            document.addEventListener('DOMContentLoaded', function () {
                const stepsPopup = document.getElementById('steps-popup');
                const closePopupButton = document.getElementById('close-popup');
                const nextStepButton = document.getElementById('next-step');
                const prevStepButton = document.getElementById('prev-step');
                const steps = document.querySelectorAll('.steps-popup-step');
                const paginationNumbers = document.querySelectorAll('.pagination-number');
                let currentStep = 0;

                setTimeout(() => {
                    stepsPopup.style.display = 'flex';
                }, 50);

                function showStep(step) {
                    steps.forEach((stepElement, index) => {
                        stepElement.classList.remove('active');
                        if (index === step) {
                            stepElement.classList.add('active');
                        }
                    });
                    paginationNumbers.forEach((num, index) => {
                        num.classList.remove('active');
                        if (index === step) {
                            num.classList.add('active');
                        }
                    });
                }

                paginationNumbers.forEach((num, index) => {
                    num.addEventListener('click', () => {
                        currentStep = index;
                        showStep(currentStep);
                    });
                });

                nextStepButton.addEventListener('click', function () {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });

                prevStepButton.addEventListener('click', function () {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });

                closePopupButton.addEventListener('click', function () {
                    stepsPopup.style.display = 'none';
                });

                showStep(currentStep);
            });

            function displayFileName() {
                const fileInput = document.getElementById('file');
                const uploadedImage = document.getElementById('uploaded-image');

                if (fileInput.files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        uploadedImage.src = e.target.result;
                        uploadedImage.style.display = 'block';
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }

            document.getElementById('scan-sampah-btn').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent form submission

    const formData = new FormData(document.querySelector('form'));

    fetch('{{ route("scan.scan") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())  // Pastikan response berupa JSON
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }

        // Tampilkan hasil scan
        showScanResult(data);  // Menampilkan hasil scan di UI
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan!');
    });
});

function showScanResult(data) {
    // Pastikan response valid dan ada data yang sesuai
    if (!data || !data.label || !data.imageUrl) {
        alert('Tidak ada data hasil scan!');
        return;
    }

    document.getElementById('scan-result').classList.remove('hidden');  // Show the scan result card
    document.getElementById('result-image').src = data.imageUrl;  // Set image yang sudah di-upload
    document.getElementById('scan-title').textContent = data.title;  // Judul jenis sampah
    document.getElementById('scan-type').textContent = `Jenis Sampah: ${data.type}`;  // Jenis sampah

    // Menambahkan saran penanganan
    const handlingTips = document.getElementById('handling-tips');
    handlingTips.innerHTML = '';  // Kosongkan sebelumnya
    data.handling.forEach(tip => {
        const li = document.createElement('li');
        li.textContent = tip;
        handlingTips.appendChild(li);
    });

    // Menambahkan saran daur ulang
    const recyclingTips = document.getElementById('recycling-tips');
    recyclingTips.innerHTML = '';  // Kosongkan sebelumnya
    data.recycling.forEach(tip => {
        const li = document.createElement('li');
        li.textContent = tip;
        recyclingTips.appendChild(li);
    });
}

        </script>

@endsection
