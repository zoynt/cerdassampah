@extends('layouts.landing')

@section('content')

<div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] z-[-999]">
    <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 490" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150">
        <path d="M 0,500 L 0,187 C 257.5,150.5 515,114 755,114 C 995,114 1217.5,150.5 1440,187 L 1440,500 L 0,500 Z" stroke="none" stroke-width="0" fill="#60cb83" fill-opacity="1" class="transition-all duration-300 ease-in-out delay-150 path-0" transform="rotate(-180 720 250)"></path>
    </svg>
</div>

<div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 py-20 lg:py-32" style="padding-bottom: 48px;">
    <div class="w-full text-center">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mt-6 mb-12 text-white" data-aos="zoom-in">
            Scan Sampah
        </h1>

        <div class="bg-white rounded-xl shadow-md mb-8 p-8" data-aos="fade-up">
            <form id="scan-form" action="{{ route('scan.scan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6 flex flex-col items-center sm:items-start">
                    <label for="file" class="block text-lg font-semibold text-gray-600">Unggah Foto Sampah</label>
                    <div class="flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg mt-2 cursor-pointer" id="upload-area" onclick="document.getElementById('file-input').click()">
                        <div class="flex flex-col items-center justify-center w-full text-center" id="upload-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="33" viewBox="0 0 50 33" fill="none" id="upload-icon">
                                <path d="M47.1056 15.5849C43.9543 12.3535 39.9359 9.09895 37.0498 5.62873C36.4114 4.86114 35.6959 4.15187 34.9106 3.51294C32.1178 1.24087 28.6148 -0.000809 25 3.95457e-07C20.9023 3.95457e-07 17.1713 1.56676 14.385 4.12812C11.931 6.38397 8.99308 8.30236 6.11843 9.9898C5.03637 10.625 4.04884 11.4234 3.19477 12.3653C1.13744 14.6343 -0.000357712 17.576 8.43599e-08 20.625C8.43599e-08 27.4519 5.60417 33 12.5 33H39.5833C45.3333 33 50 28.38 50 22.6875C50 19.9226 48.8988 17.4237 47.1056 15.5849ZM39.5833 28.875H12.5C7.89583 28.875 4.16667 25.1831 4.16667 20.625C4.16667 16.3969 7.35417 12.87 11.5833 12.4369C12.963 12.2965 14.2032 11.4751 14.8542 10.2506C15.8124 8.40438 17.2666 6.85565 19.0567 5.77488C20.8468 4.69411 22.9033 4.12321 25 4.125C30.4583 4.125 35.1667 7.96125 36.2292 13.2619C36.5985 15.0901 38.142 16.4473 40.0025 16.5797L40.0417 16.5825C41.6078 16.6868 43.0759 17.3745 44.1505 18.5072C45.225 19.6399 45.8263 21.1336 45.8333 22.6875C45.8333 26.0906 43.0208 28.875 39.5833 28.875ZM19.3418 15.9141C18.3589 16.8871 19.048 18.5625 20.431 18.5625C21.286 18.5625 21.9792 19.2556 21.9792 20.1107V21.7292C21.9792 23.3975 23.3316 24.75 25 24.75C26.6684 24.75 28.0208 23.3975 28.0208 21.7292V20.1107C28.0208 19.2556 28.714 18.5625 29.569 18.5625C30.952 18.5625 31.6411 16.8871 30.6582 15.9141L27.4112 12.6996C26.0756 11.3774 23.9244 11.3774 22.5888 12.6996L19.3418 15.9141Z" fill="#757575" />
                            </svg>
                            <span class="text-lg text-gray-500" id="upload-text">Unggah atau ambil foto di sini</span>
                            <img id="uploaded-image" src="" alt="Preview Gambar" class="hidden mt-2 max-w-full h-auto rounded-lg">
                        </div>
                    </div>
                    <input type="file" id="file-input" name="file" class="hidden" accept=".png, .jpg, .jpeg">
                </div>

                <div class="mt-6">
                    <button id="scan-sampah-btn" type="submit" class="w-full py-3 text-white text-lg bg-green-600 font-semibold rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                        Scan Sampah
                    </button>
                </div>
            </form>
        </div>

        <div id="error-alert" class="hidden flex flex-col items-center justify-center w-full text-white p-4 mt-6 rounded-lg bg-red-600" data-aos="fade-up">
            <span class="font-bold text-lg">Terjadi Kesalahan</span>
            <span id="error-message" class="text-sm mt-1">Server AI gagal memproses gambar.</span>
        </div>

        <div id="scan-results-container" class="hidden text-left mt-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Hasil Scan</h2>

            <!-- <div class="mb-8 p-4 bg-white rounded-xl shadow-lg border">
                <img id="result-image-display" src="" alt="Hasil Deteksi Sampah" class="w-full h-auto rounded-lg">
            </div> -->

            <div class="w-full mx-auto mb-8 p-4 bg-white rounded-xl shadow-lg border">
                <img id="result-image-display" src="" alt="Hasil Deteksi Sampah" class="w-full h-auto rounded-lg">
            </div>
            
            <div id="accordion-container" class="space-y-5"></div>
        </div>

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
    </div>
</div>

<style>
    html, body {
        overflow-x: hidden;
    }

    /* ----- Gaya Umum & Responsif ----- */
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #f4f4f9;
    }
    @media (max-width: 768px) {
        .flex-col-reverse {
            flex-direction: column-reverse;
        }
        .lg\:py-32 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
    }

    /* ----- Gaya Area Upload ----- */
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
    #upload-area.file-selected #upload-text,
    #upload-area.file-selected #upload-icon {
        display: none;
    }
    #upload-area.file-selected #uploaded-image {
        display: block;
        max-width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ----- Gaya Card Hasil Scan (Lama) ----- */
    #scan-result {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }
    #scan-result.scan-card {
        background: #5E936C;
        color: #fff;
    }
    #scan-result .scan-card__title {
        text-align: center;
        font-weight: 800;
        font-size: 1.75rem;
        margin: 0 0 16px 0;
    }
    #scan-result .scan-card__grid {
        display: grid;
        grid-template-columns: 1fr 1.6fr;
        gap: 24px;
        align-items: stretch;
    }
    #scan-result .scan-card__photo {
        background: #fff;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    #scan-result .scan-card__photo img {
        width: 100%;
        max-width: 360px;
        aspect-ratio: 1/1;
        object-fit: contain;
        border-radius: 12px;
    }
    #scan-result .scan-card__info {
        background: #fff;
        color: #374151;
        border-radius: 16px;
        padding: 20px 24px;
    }
    #scan-result .badge {
        display: inline-block;
        border-radius: 8px;
        padding: 8px 14px;
        font-weight: 700;
        line-height: 1;
    }
    #scan-result .badge--solid { background: #5E936C; color: #fff; }
    #scan-result .badge--soft { background: #E6F4EA; color: #2E6141; padding: 6px 12px; }
    #scan-result .scan-card__type { margin: 10px 0 14px; }
    #scan-result .section-chip {
        display: inline-block;
        background: #5E936C;
        color: #fff;
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 700;
    }
    #scan-result .scan-card__list {
        margin: 10px 0 0 22px;
        color: #374151;
        list-style: decimal;
    }
    #scan-result .scan-card__list li { margin: 4px 0; }
    @media (max-width: 1024px) {
        #scan-result .scan-card__grid { grid-template-columns: 1fr; }
        #scan-result .scan-card__photo img { max-width: 100%; }
    }

    /* ----- Gaya Popup ----- */
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
        padding: 10px 0;
    }
    .steps-popup-step { display: none; }
    .steps-popup-step.active { display: block; }
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
    .pagination-arrow:hover { color: #5E936C; }
    .pagination-numbers { display: flex; gap: 10px; }
    .pagination-number {
        font-size: 1.2rem;
        cursor: pointer;
        user-select: none;
        padding: 10px 16px;
        border-radius: 50%;
        transition: all 0.3s ease;
        border: 1px solid #ddd;
        width: 40px;
        height: 40px;
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
    .steps-popup button:hover { background-color: #4a7a57; }
    @media (max-width: 768px) {
        .steps-popup { width: 90%; padding: 15px; }
        .steps-popup-title { font-size: 1.3rem; }
    }

/* TAMBAHKAN CSS INI DI DALAM TAG <style> */

.accordion-content-inner {
    background-color: #f9fafb; /* Warna latar belakang abu-abu sangat muda */
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px; /* Jarak antar bagian */
}

.accordion-section {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
}

.accordion-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 10px;
}

.accordion-section-title svg {
    width: 20px;
    height: 20px;
    color: #5E936C; /* Warna hijau ikon */
}

.accordion-section ul {
    list-style-position: outside;
    padding-left: 20px;
    color: #4b5563;
    display: flex;
    flex-direction: column;
    gap: 6px; /* Jarak antar list item */
}

    #scan-sampah-btn:disabled {
    background-color: #94a3b8; /* Warna abu-abu dari desain Anda */
    cursor: not-allowed;
    }

    /* Gaya untuk tombol saat terjadi error */
    #scan-sampah-btn.is-error {
        background-color: #dc2626; /* Warna merah */
        transition: background-color 0.3s;
    }

    #scan-sampah-btn.is-error:hover {
        background-color: #b91c1c; /* Warna merah lebih gelap saat hover */
    }

    .animated-ellipsis::after {
    overflow: hidden;
    display: inline-block;
    vertical-align: bottom;
    animation: ellipsis-animation 1.4s infinite;
    content: "\\2026"; /* karakter elipsis Unicode (...) */
    width: 0px;
    }

    @keyframes ellipsis-animation {
    0% {
        content: ".";
    }
    33% {
        content: "..";
    }
    66% {
        content: "...";
    }
    100% {
        content: ".";
    }
    }
</style>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeColorMap = {
            'anorganik': 'text-yellow-600',
            'organik': 'text-green-600',
            'b3': 'text-red-600',
            'default': 'text-gray-500'
        };

        // --- Inisialisasi Variabel ---
        const API_URL = "{{ route('scan.scan') }}";
    const scanForm = document.getElementById('scan-form');
    const fileInput = document.getElementById('file-input');
    const uploadedImage = document.getElementById('uploaded-image');
    const uploadArea = document.getElementById('upload-area');
    const scanButton = document.getElementById('scan-sampah-btn');
    const errorAlert = document.getElementById('error-alert');
    const errorMessage = document.getElementById('error-message');
    const resultsContainer = document.getElementById('scan-results-container');
    const accordionContainer = document.getElementById('accordion-container');
    const resultImageDisplay = document.getElementById('result-image-display');

    // --- Event Listener untuk Preview Gambar ---
    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadedImage.src = e.target.result;
                uploadedImage.classList.remove('hidden');
                uploadArea.classList.add('file-selected');
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    });

    // --- Event Listener untuk Tombol Scan ---
    scanForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!fileInput.files.length) {
            Swal.fire('Perhatian!', 'Silakan pilih gambar terlebih dahulu!', 'warning');
            return;
        }
        setBusy(true);
        errorAlert.classList.add('hidden');
        resultsContainer.classList.add('hidden');

        const formData = new FormData(scanForm);

        fetch("{{ route('scan.scan') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            })
            .then(async (res) => {
                if (!res.ok) {
                    const errorData = await res.json().catch(() => ({
                        message: `Server Error: ${res.status}`
                    }));
                    throw new Error(errorData.message || 'Terjadi kesalahan saat memproses gambar.');
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    // Tampilkan gambar hasil utama dari server AI
                    if (data.result_image_url) {
                        resultImageDisplay.src = data.result_image_url;
                        resultImageDisplay.style.display = 'block';
                    } else {
                        resultImageDisplay.style.display = 'none';
                    }

                    // Render akordion untuk detail prediksi
                renderAccordionResults(data.predictions);
                } else {
                    throw new Error(data.message || 'Format respons tidak valid atau tidak ada prediksi.');
                }
            })
            .catch(err => {
                showErrorAlert(err.message);
            })
            .finally(() => {
                setBusy(false);
            });
    });

    // --- Fungsi-fungsi Helper ---

    let ellipsisInterval = null;

    // ... (Event Listener untuk Preview Gambar tetap sama)
    fileInput.addEventListener('change', function() { /* ... */ });

    // --- Event Listener untuk Tombol Scan ---
    scanForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // ... (validasi file tetap sama)
        setBusy(true); // Memulai animasi
        
        // ... (sisa logika fetch API tetap sama)
        const formData = new FormData(scanForm);
        fetch("{{ route('scan.scan') }}", { /* ... */ })
            .then(async (res) => { /* ... */ })
            .then(data => { /* ... */ })
            .catch(err => { /* ... */ })
            .finally(() => {
                setBusy(false); // Menghentikan animasi
            });
    });

    // --- [DIUBAH] Fungsi setBusy dengan animasi JavaScript ---
    function setBusy(state) {
        scanButton.disabled = state;

        // Selalu hentikan interval sebelumnya untuk mencegah tumpang tindih
        clearInterval(ellipsisInterval);

        if (state) {
            // Tampilan saat proses scan/loading sedang berjalan
            let dotCount = 1;
            
            // Set HTML awal dengan span target untuk teks
            scanButton.innerHTML = `
                <div class="flex items-center justify-center">
                    <span id="loading-text">Menganalisis.</span>
                </div>
            `;
            
            const loadingTextElement = scanButton.querySelector('#loading-text');

            // Mulai interval untuk menganimasikan titik-titik
            ellipsisInterval = setInterval(() => {
                dotCount = (dotCount % 3) + 1; // Membuat siklus: 1, 2, 3, 1, 2, 3, ...
                if (loadingTextElement) {
                   loadingTextElement.textContent = 'Menganalisis' + '.'.repeat(dotCount);
                }
            }, 400); // Ganti teks setiap 400 milidetik

        } else {
            // Kembalikan ke tampilan semula jika sudah selesai
            scanButton.innerHTML = 'Scan Sampah';
        }
    }

    function showErrorAlert(message) {
        errorMessage.textContent = message;
        errorAlert.classList.remove('hidden');
        setTimeout(() => {
            errorAlert.classList.add('hidden');
        }, 5000);
    }

    function renderAccordionResults(results) {
        resultsContainer.classList.remove('hidden');
        accordionContainer.innerHTML = '';

    // TAMBAHKAN KODE INI
    const labelColorMap = {
        'Organik': '#11b44cff',
        'Anorganik': '#f6f101ff',
        'Residu': '#fc5f5fff',
        // Tambahkan label lain jika ada di masa depan
    }

        if (!Array.isArray(results) || results.length === 0) {
            accordionContainer.innerHTML = '<p class="text-center text-gray-500 py-4">Tidak ada objek yang terdeteksi.</p>';
        } else {
            results.forEach((result, index) => {
                const handlingList = (result.handlingTips || []).map(tip => `<li>${tip}</li>`).join('');
                const recyclingList = (result.recyclingTips || []).map(tip => `<li>${tip}</li>`).join('');

                // Label Akurasi (Perubahan di sini)
                const accuracyLabel = result.confidence 
                    ? `<p class="text-md font-bold text-gray-500">Akurasi: ${Math.round(result.confidence * 100)}%</p>` 
                    : '<p class="text-md font-bold text-gray-500">Akurasi tidak tersedia</p>';
                
                // Judul Utama (Perubahan di sini)
                const resultLabel = result.label;

                const accordionItemHTML = `
                    <div class="accordion-item bg-white border border-gray-200 rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <button class="accordion-header w-full flex justify-between items-center py-5 px-6 text-left">
                            <div>
                                <p class="text-lg font-bold text-gray-800">
                                    ${'Objek ' + (index + 1)}: <span style="color: ${labelColorMap[result.label] || 'inherit'}">${resultLabel}</span>
                                </p>
                                ${accuracyLabel}
                            </div>
                            <div class="pr-2">
                                <svg class="accordion-chevron w-6 h-6 text-gray-400 transition-transform duration-300 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                            </div>
                        </button>
                        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                            <div class="p-6 bg-green-50 border-t border-green-200 space-y-4">
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg  class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                        fill="currentColor" viewBox="0 0 24 24" >
                                        <path d="M11 11h2v6h-2zM11 7h2v2h-2z"></path><path d="M12 22c5.51 0 10-4.49 10-10S17.51 2 12 2 2 6.49 2 12s4.49 10 10 10m0-18c4.41 0 8 3.59 8 8s-3.59 8-8 8-8-3.59-8-8 3.59-8 8-8"></path>
                                        </svg>
                                        <h3 class="font-semibold text-base text-gray-800">Deskripsi & Fakta Menarik</h3>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed pl-9">${result.description || 'Deskripsi untuk item ini tidak tersedia.'}</p>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                        fill="currentColor" viewBox="0 0 24 24" >
                                        <path d="m11 11.59-1.29-1.3-1.42 1.42 2.71 2.7 4.71-4.7-1.42-1.42z"></path><path d="M12 3C6.49 3 2 6.59 2 11c0 2.91 1.9 5.51 5 6.93V23l5.34-4c5.36-.14 9.66-3.68 9.66-8s-4.49-8-10-8m0 14h-.33L9 19v-2.42l-.64-.25C5.67 15.29 4 13.25 4 10.99c0-3.31 3.59-6 8-6s8 2.69 8 6-3.59 6-8 6Z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-base text-gray-800">Saran Penanganan</h3>
                                    </div>
                                    <ol class="list-decimal list-inside text-gray-600 space-y-2 text-sm">${handlingList}</ol>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg  class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                        fill="currentColor" viewBox="0 0 24 24" >
                                        <path d="m20.39,14.04l-1.75.97.84,1.51c.09.16.13.33.13.51,0,.17-.05.33-.14.48-.09.15-.21.27-.35.36-.15.09-.33.14-.51.14h-6.6v-2l-4,3,4,3v-2h6.6c.54,0,1.07-.14,1.53-.42.43-.26.79-.62,1.05-1.06.26-.44.4-.93.41-1.43.02-.53-.11-1.07-.38-1.54l-.84-1.51Z"></path><path d="m4.89,17.86c-.14-.08-.26-.21-.35-.36-.09-.15-.14-.31-.14-.48,0-.17.04-.35.13-.51l3.35-6.03,1.72,1-.6-4.96-4.6,1.96,1.74,1-3.37,6.06c-.26.48-.39,1.01-.38,1.54.01.5.16,1,.42,1.44.26.44.62.81,1.05,1.06.46.27.99.42,1.53.42h1.65v-2h-1.65c-.19,0-.36-.05-.51-.14Z"></path><path d="m11.13,4.63c.09-.17.22-.3.38-.39.3-.17.69-.17.99,0,.16.09.28.22.38.39l3.27,5.88-1.74,1,4.6,1.96.6-4.96-1.72,1-3.25-5.85c-.27-.49-.67-.89-1.14-1.16-.89-.5-2.06-.5-2.96,0-.48.27-.87.67-1.14,1.16l-.81,1.46,1.75.97.81-1.46Z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-base text-gray-800">Daur Ulang</h3>
                                    </div>
                                    <ol class="list-decimal list-inside text-gray-600 space-y-2 text-sm">${recyclingList}</ol>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                accordionContainer.innerHTML += accordionItemHTML;
            });
            addAccordionListeners();
        }
        resultsContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function addAccordionListeners() {
        const accordionHeaders = document.querySelectorAll('.accordion-header');
        accordionHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const chevron = header.querySelector('.accordion-chevron');
                const isActive = header.classList.contains('active');
                document.querySelectorAll('.accordion-header.active').forEach(activeHeader => {
                    if (activeHeader !== header) {
                        activeHeader.classList.remove('active');
                        activeHeader.nextElementSibling.style.maxHeight = null;
                        activeHeader.querySelector('.accordion-chevron').classList.remove('rotate-180');
                    }
                });
                if (!isActive) {
                    header.classList.add('active');
                    content.style.maxHeight = content.scrollHeight + "px";
                    chevron.classList.add('rotate-180');
                } else {
                    header.classList.remove('active');
                    content.style.maxHeight = null;
                    chevron.classList.remove('rotate-180');
                }
            });
        });
    }
    });

    // --- Logika untuk Popup ---
    const stepsPopup = document.getElementById('steps-popup');
    const closePopupButton = document.getElementById('close-popup');
    const nextStepButton = document.getElementById('next-step');
    const prevStepButton = document.getElementById('prev-step');
    const steps = document.querySelectorAll('.steps-popup-step');
    const paginationNumbers = document.querySelectorAll('.pagination-number');
    let currentStep = 0;

    if (stepsPopup) {
        setTimeout(() => {
            stepsPopup.style.display = 'flex';
        }, 50);

        function showStep(stepIndex) {
            steps.forEach((stepEl, i) => stepEl.classList.toggle('active', i === stepIndex));
            paginationNumbers.forEach((numEl, i) => numEl.classList.toggle('active', i === stepIndex));
        }

        paginationNumbers.forEach((num, i) => {
            num.addEventListener('click', () => {
                currentStep = i;
                showStep(currentStep);
            });
        });

        if (nextStepButton) {
            nextStepButton.addEventListener('click', () => {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        }

        if (prevStepButton) {
            prevStepButton.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        }

        if (closePopupButton) {
            closePopupButton.addEventListener('click', () => {
                stepsPopup.style.display = 'none';
            });
        }

        showStep(currentStep);
    }
</script>
@endpush
@endsection
