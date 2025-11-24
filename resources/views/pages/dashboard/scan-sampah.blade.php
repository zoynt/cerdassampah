@extends('layouts.dashboard')

@section('title', 'Scan Sampah')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

@section('content')
    {{-- Bagian Header (Tidak Berubah) --}}
    <div class="relative bg-gradient-to-b from-green-700 via-green-700 to-green-500 -mt-4 sm:-mt-6 text-white mb-6">
        <div class="relative z-10 p-8 pt-10 pb-20 flex flex-col justify-center items-center text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight text-white">
                Scan Sampah
            </h1>
            <p class="mt-2 text-lg text-white/90">Unggah foto untuk mengetahui jenis sampah Anda.</p>
        </div>
        <div class="absolute bottom-[-1px] left-0 w-full text-slate-50">
            <svg viewBox="0 0 1440 120" fill="currentColor" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M1440,120H0V20.48c0,0,202.4,69.52,480,69.52s480-139.04,960-69.52V120Z"></path>
            </svg>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8 -mt-16 relative z-10">
        {{-- Bagian Form Upload (Sedikit Perubahan pada Input File) --}}
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <form id="scan-form" action="{{ route('scan.scan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="file" class="block text-lg font-semibold text-gray-700">Unggah Foto Sampah</label>
                    <div class="relative flex items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg mt-2 cursor-pointer transition-colors hover:border-green-500"
                        id="upload-area" onclick="document.getElementById('file').click()">
                        <div class="flex flex-col items-center justify-center w-full text-center" id="upload-content">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="text-lg text-gray-500" id="upload-text">Klik untuk unggah foto</span>
                        </div>
                        <img id="image-preview" src="#" alt="Pratinjau Gambar"
                            class="absolute h-full w-full object-contain rounded-lg hidden p-2">
                    </div>
                    {{-- PERUBAHAN PENTING: name="image" diubah menjadi name="file" agar sesuai dengan backend --}}
                    <input type="file" id="file" name="file" class="hidden" accept=".png, .jpg, .jpeg"
                        onchange="previewImage(event)">
                </div>
                <div class="mt-6">
                    <button id="scan-sampah-btn" type="submit"
                        class="w-full py-3 text-white text-lg bg-green-700 font-semibold rounded-lg hover:bg-green-800 focus:ring-4 focus:ring-green-300 transition-all duration-300 disabled:bg-gray-400">
                        Scan Sampah
                    </button>
                </div>
            </form>
        </div>

        {{-- Bagian Notifikasi (Tidak Berubah) --}}
        <div id="notification-alert" class="hidden mt-8 rounded-2xl p-4 text-center shadow-lg transition-all duration-300">
            <h3 id="notification-message" class="font-bold text-lg"></h3>
            <p id="notification-details" class="text-sm"></p>
            <a id="notification-action-btn" href="{{ route('dashboard') }}"
                class="hidden mt-3 px-5 py-2 font-semibold rounded-lg">
                Kembali ke Dashboard
            </a>
        </div>

        {{-- PERUBAHAN UTAMA: Kontainer Hasil Scan Diganti Total --}}
        <div id="scan-results-container" class="hidden mt-8 text-gray-700">
            <h2 class="text-center font-extrabold text-2xl mb-6">Hasil Scan</h2>
            {{-- Tempat untuk menampilkan gambar hasil anotasi dari server --}}
            <div class="w-full max-w-2xl mx-auto mb-8 p-2 bg-white rounded-xl shadow-lg border">
                <img id="result-image-display" src="" alt="Hasil Deteksi Sampah"
                    class="w-full h-auto rounded-lg" style="display: none;">
            </div>
            {{-- Tempat untuk Akordeon hasil deteksi --}}
            <div id="accordion-container" class="space-y-4"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk preview gambar (Tidak Berubah)
        function previewImage(event) {
            const fileInput = event.target;
            const uploadArea = document.getElementById('upload-area');
            const uploadContent = document.getElementById('upload-content');
            const imagePreview = document.getElementById('image-preview');
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadContent.classList.add('hidden');
                    uploadArea.classList.add('border-green-500');
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                imagePreview.src = "#";
                imagePreview.classList.add('hidden');
                uploadContent.classList.remove('hidden');
                uploadArea.classList.remove('border-green-500');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // --- Definisi Elemen DOM ---
            const scanForm = document.getElementById('scan-form');
            const fileInput = document.getElementById('file');
            const scanButton = document.getElementById('scan-sampah-btn');
            const resultsContainer = document.getElementById('scan-results-container');
            const accordionContainer = document.getElementById('accordion-container');
            const resultImageDisplay = document.getElementById('result-image-display');

            let ellipsisInterval = null;

            // --- Fungsi Notifikasi (Tidak Berubah) ---
            function showNotification(type, message, details = '') {
                const alertBox = document.getElementById('notification-alert');
                const messageEl = document.getElementById('notification-message');
                const detailsEl = document.getElementById('notification-details');
                const buttonEl = document.getElementById('notification-action-btn');
                alertBox.className =
                    'mt-8 rounded-2xl p-4 text-center shadow-lg transition-all duration-300';
                buttonEl.classList.add('hidden');
                if (type === 'success') {
                    alertBox.classList.add('bg-amber-400', 'text-amber-900');
                    buttonEl.classList.remove('hidden');
                    buttonEl.classList.add('inline-block', 'bg-green-700', 'text-white',
                        'hover:bg-green-800');
                } else if (type === 'info') {
                    alertBox.classList.add('bg-blue-500', 'text-white');
                } else {
                    alertBox.classList.add('bg-red-600', 'text-white');
                }
                messageEl.textContent = message;
                detailsEl.textContent = details;
                alertBox.classList.remove('hidden');
            }

            // --- [BARU] Fungsi Loading Tombol yang lebih baik ---
            function setBusy(state) {
                scanButton.disabled = state;
                clearInterval(ellipsisInterval);

                if (state) {
                    let dotCount = 1;
                    scanButton.innerHTML = `<span id="loading-text">Menganalisis.</span>`;
                    const loadingTextElement = scanButton.querySelector('#loading-text');

                    ellipsisInterval = setInterval(() => {
                        dotCount = (dotCount % 3) + 1;
                        if (loadingTextElement) {
                            loadingTextElement.textContent = 'Menganalisis' + '.'.repeat(dotCount);
                        }
                    }, 400);
                } else {
                    scanButton.innerHTML = 'Scan Sampah';
                }
            }

            // --- [BARU] Logika Fetch API dan Tampilan Hasil ---
            scanForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!fileInput.files.length) {
                    showNotification('error', 'Peringatan', 'Silakan pilih gambar terlebih dahulu!');
                    return;
                }

                setBusy(true);
                document.getElementById('notification-alert').classList.add('hidden');
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
                            throw new Error(errorData.error || errorData.message ||
                                'Terjadi kesalahan saat memproses gambar.');
                        }
                        return res.json();
                    })
                    .then(data => {
                        // 1. Proses notifikasi quest (logika lama dipertahankan)
                        if (data.questResult) {
                            if (data.questResult.completed) {
                                showNotification('success', data.questResult.message,
                                    `Anda mendapatkan +${data.questResult.points_awarded} poin!`);
                            } else {
                                showNotification('info', 'Misi Sudah Selesai', data.questResult
                                    .message);
                            }
                        }

                        // 2. Proses hasil scan (logika baru dari halaman landing)
                        if (data && data.success) {
                            if (data.result_image_url) {
                                resultImageDisplay.src = data.result_image_url;
                                resultImageDisplay.style.display = 'block';
                            } else {
                                // Jika tidak ada gambar anotasi, gunakan gambar preview
                                resultImageDisplay.src = document.getElementById('image-preview').src;
                                resultImageDisplay.style.display = 'block';
                            }
                            renderAccordionResults(data.predictions);
                        } else {
                            // Menangani jika API mengembalikan `success: false` atau format data salah
                            throw new Error(data.message ||
                                'Format respons tidak valid atau tidak ada prediksi.');
                        }
                    })
                    .catch(err => {
                        showNotification('error', 'Terjadi Kesalahan', err.message);
                    })
                    .finally(() => {
                        setBusy(false);
                    });
            });

            // --- [BARU] Fungsi untuk membuat Akordeon Hasil ---
            function renderAccordionResults(results) {
                resultsContainer.classList.remove('hidden');
                accordionContainer.innerHTML = '';

                const labelColorMap = {
                    'Organik': '#11b44cff',
                    'Anorganik': '#f6f101ff',
                    'Residu': '#fc5f5fff',
                };

                if (!Array.isArray(results) || results.length === 0) {
                    accordionContainer.innerHTML =
                        '<p class="text-center text-gray-500 py-4">Tidak ada objek sampah yang terdeteksi pada gambar.</p>';
                } else {
                    results.forEach((result, index) => {
                        const handlingList = (result.handlingTips || []).map(tip => `<li>${tip}</li>`).join('');
                        const recyclingList = (result.recyclingTips || []).map(tip => `<li>${tip}</li>`).join('');
                        const accuracyLabel = result.confidence ?
                            `<p class="text-sm font-medium text-gray-500">Akurasi: ${Math.round(result.confidence * 100)}%</p>` : '';
                        
                        const accordionItemHTML = `
                            <div class="accordion-item bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm transition-all duration-300">
                                <button class="accordion-header w-full flex justify-between items-center py-4 px-6 text-left">
                                    <div>
                                        <p class="text-lg font-bold text-gray-800">
                                            ${'Objek ' + (index + 1)}: <span style="color: ${labelColorMap[result.label] || '#374151'}">${result.label}</span>
                                        </p>
                                        ${accuracyLabel}
                                    </div>
                                    <div class="pr-2">
                                        <svg class="accordion-chevron w-6 h-6 text-gray-400 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                                    </div>
                                </button>
                                <div class="accordion-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                                    <div class="p-5 bg-gray-50 border-t border-gray-200 space-y-4">
                                        <div class="p-4 rounded-lg bg-white border">
                                           <h3 class="font-semibold text-base text-gray-800 mb-2">Deskripsi</h3>
                                           <p class="text-gray-600 text-sm leading-relaxed">${result.description || 'Deskripsi tidak tersedia.'}</p>
                                        </div>
                                        <div class="p-4 rounded-lg bg-white border">
                                           <h3 class="font-semibold text-base text-gray-800 mb-2">Saran Penanganan</h3>
                                           <ol class="list-decimal list-inside text-gray-600 space-y-1 text-sm">${handlingList || '<li>Informasi tidak tersedia.</li>'}</ol>
                                        </div>
                                        <div class="p-4 rounded-lg bg-white border">
                                           <h3 class="font-semibold text-base text-gray-800 mb-2">Daur Ulang</h3>
                                           <ol class="list-decimal list-inside text-gray-600 space-y-1 text-sm">${recyclingList || '<li>Informasi tidak tersedia.</li>'}</ol>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        accordionContainer.innerHTML += accordionItemHTML;
                    });
                    addAccordionListeners();
                }

                resultsContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            // --- [BARU] Fungsi untuk interaksi Akordeon ---
            function addAccordionListeners() {
                const accordionHeaders = document.querySelectorAll('.accordion-header');
                accordionHeaders.forEach(header => {
                    header.addEventListener('click', () => {
                        const content = header.nextElementSibling;
                        const chevron = header.querySelector('.accordion-chevron');
                        const isActive = header.classList.contains('active');

                        // Menutup semua akordeon lain
                        document.querySelectorAll('.accordion-header.active').forEach(activeHeader => {
                            if (activeHeader !== header) {
                                activeHeader.classList.remove('active');
                                activeHeader.nextElementSibling.style.maxHeight = null;
                                activeHeader.querySelector('.accordion-chevron').classList.remove('rotate-180');
                            }
                        });

                        // Membuka atau menutup akordeon yang diklik
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
    </script>
@endpush