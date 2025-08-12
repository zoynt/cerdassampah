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
                <section class="hero-section">
                </section>

                <div class="container content-wrapper">
                    <div class="image-preview-box" id="image-preview-box">
                        <div id="placeholder-content">
                            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21 3.00002H3C1.89543 3.00002 1 3.89545 1 5.00002V19C1 20.1046 1.89543 21 3 21H21C22.1046 21 23 20.1046 23 19V5.00002C23 3.89545 22.1046 3.00002 21 3.00002ZM3 19.0001V5.00006L3.00222 5.00002H21V19H3V19.0001Z"
                                    fill="#cccccc" />
                                <path d="M5.5 15.5L10.5 9.5L14.5 14.5L16.5 12L21 17.5H7.25L5.5 15.5Z" fill="#cccccc" />
                                <path
                                    d="M15 10C16.1046 10 17 9.10457 17 8C17 6.89543 16.1046 6 15 6C13.8954 6 13 6.89543 13 8C13 9.10457 13.8954 10 15 10Z"
                                    fill="#cccccc" />
                            </svg>
                        </div>
                        <video id="camera-stream" autoplay playsinline
                            style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 15px;"></video>
                    </div>
                    <div class="action-buttons" id="action-buttons">
                        <button class="btn btn-secondary" id="upload-btn">Unggah Foto</button>
                        <button class="btn btn-secondary" id="camera-btn">Ambil Foto</button>
                    </div>
                    <div class="camera-controls" id="camera-controls" style="display: none; gap: 20px;">
                        <button class="btn btn-secondary" id="capture-btn">Jepret Foto</button>
                        <button class="btn btn-secondary" id="cancel-camera-btn">Batal</button>
                    </div>
                    <input type="file" id="file-input" accept="image/*" style="display: none;">
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Langkah -->
    <div id="steps-popup" class="steps-popup-overlay" style="display: none;">
        <div class="steps-popup">
            <h2 class="steps-popup-title mb-6">Langkah Mudah Menggunakan Fitur Scan</h2>
                        <!-- Tombol Navigasi Pagination -->
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

        .steps-popup-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #333;
        }

        .steps-popup-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 10px 0;
        }

        .steps-popup-step {
            display: none;
        }

        .steps-popup-step.active {
            display: block;
        }

        /* --- Pagination --- */
        .pagination-navigation {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .pagination-arrow {
            font-size: 2rem;
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
        }

        .pagination-arrow:hover {
            color: #5E936C;
        }

        .pagination-numbers {
            display: flex;
            gap: 10px;
        }

        .pagination-number {
            font-size: 1.2rem;
            cursor: pointer;
            user-select: none;
            padding: 10px 16px; /* padding adjusted */
            border-radius: 50%; /* ensures it is perfectly round */
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            width: 40px;
            height: 40px; /* ensures square size */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-number.active {
            background-color: #5E936C;
            color: white;
            font-weight: 600;
        }

        .steps-popup button {
            padding: 12px 24px;
            background-color: #5E936C;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .steps-popup button:hover {
            background-color: #4a7a57;
        }

        /* --- Responsif --- */
        @media (max-width: 768px) {
            .steps-popup {
                width: 90%;
                padding: 15px;
            }

            .steps-popup-title {
                font-size: 1.3rem;
            }

            .steps-popup button {
                font-size: 1rem;
                padding: 10px 20px;
            }

            .pagination-arrow {
                font-size: 1.5rem;
            }

            .pagination-number {
                font-size: 1rem;
                padding: 6px 12px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stepsPopup = document.getElementById('steps-popup');
            const closePopupButton = document.getElementById('close-popup');
            const nextStepButton = document.getElementById('next-step');
            const prevStepButton = document.getElementById('prev-step');
            const steps = document.querySelectorAll('.steps-popup-step');
            const paginationNumbers = document.querySelectorAll('.pagination-number');
            let currentStep = 0;

            // Popup muncul setiap kali halaman di-refresh
            setTimeout(() => {
                stepsPopup.style.display = 'flex'; // Tampilkan popup setelah halaman termuat
            }, 500); // Delay agar halaman termuat dengan baik

            // Menampilkan langkah-langkah
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

            // Menangani klik pada pagination number
            paginationNumbers.forEach((num, index) => {
                num.addEventListener('click', () => {
                    currentStep = index;
                    showStep(currentStep);
                });
            });

            // Navigasi ke langkah berikutnya
            nextStepButton.addEventListener('click', function () {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            // Navigasi ke langkah sebelumnya
            prevStepButton.addEventListener('click', function () {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Menutup popup ketika tombol tutup diklik
            closePopupButton.addEventListener('click', function () {
                stepsPopup.style.display = 'none';
            });

            // Tampilkan langkah pertama saat popup dibuka
            showStep(currentStep);
        });
    </script>

@endsection
