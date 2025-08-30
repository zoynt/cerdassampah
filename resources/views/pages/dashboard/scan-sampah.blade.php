@extends('layouts.dashboard')

@section('title', 'Scan Sampah')

@section('content')

    <div class="relative bg-gradient-to-b from-green-700 via-green-700 to-green-500 -mt-4 sm:-mt-6 text-white mb-6">

        <div class="relative z-10 p-8 pt-10 pb-20 flex flex-col justify-center items-center text-center">

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight text-white">
                Scan Sampah
            </h1>
            <p class="mt-2 text-lg text-white/90">Unggah foto untuk mengetahui jenis sampah Anda.</p>
            <div class="flex items-center ju
            stify-center w-24 h-24 mb-4 rounded-full overflow-hidden">

            </div>

        </div>

        {{-- SVG Gelombang dengan warna yang cocok dengan latar belakang konten --}}
        <div class="absolute bottom-[-1px] left-0 w-full text-slate-50">
            <svg viewBox="0 0 1440 120" fill="currentColor" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M1440,120H0V20.48c0,0,202.4,69.52,480,69.52s480-139.04,960-69.52V120Z"></path>
            </svg>
        </div>
    </div>


    {{-- KONTEN UTAMA --}}
    <div class="p-4 sm:p-6 lg:p-8 -mt-16 relative z-10">
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <form id="scan-form" action="{{ route('scan.scan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="file" class="block text-lg font-semibold text-gray-700">Unggah Foto Sampah</label>
                    <div class="relative flex items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg mt-2 cursor-pointer transition-colors hover:border-green-500"
                        id="upload-area" onclick="document.getElementById('file').click()">

                        <div class="flex flex-col items-center justify-center w-full text-center" id="upload-content">
                            <svg id="upload-icon" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="text-lg text-gray-500" id="upload-text">Klik untuk unggah foto</span>
                        </div>

                        <img id="image-preview" src="#" alt="Pratinjau Gambar"
                            class="absolute h-full w-full object-contain rounded-lg hidden p-2">
                    </div>
                    <input type="file" id="file" name="image" class="hidden" accept=".png, .jpg, .jpeg"
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

        <div id="scan-result" class="hidden mt-8 bg-green-700 text-white rounded-2xl shadow-xl p-6">
            <h2 class="text-center font-extrabold text-2xl mb-4">Hasil Scan</h2>
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.6fr] gap-6 items-stretch">
                <div class="bg-white rounded-2xl flex items-center justify-center p-4">
                    <img id="result-image" src="" alt="Hasil Scan"
                        class="w-full max-w-[360px] aspect-square object-contain rounded-xl">
                </div>
                <div class="bg-white text-gray-700 rounded-2xl p-5 sm:p-6 text-left">
                    <div class="mb-4">
                        <span id="scan-title"
                            class="inline-block rounded-lg px-4 py-2 font-bold leading-none bg-green-700 text-white">—</span>
                    </div>
                    <p class="my-3"></p>
                    <p id="scan-desc" class="hidden text-gray-700 leading-relaxed mb-3"></p>
                    <div class="mb-4">
                        <span class="inline-block bg-green-700 text-white rounded-lg px-3 py-1 font-bold">Saran
                            Penanganan:</span>
                        <ol id="handling-tips" class="list-decimal pl-5 mt-2 space-y-1"></ol>
                    </div>
                    <div>
                        <span class="inline-block bg-green-700 text-white rounded-lg px-3 py-1 font-bold">Daur Ulang:</span>
                        <ol id="recycling-tips" class="list-decimal pl-5 mt-2 space-y-1"></ol>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@push('scripts')
    <script>
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
                    uploadArea.classList.add('file-selected');
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                imagePreview.src = "#";
                imagePreview.classList.add('hidden');
                uploadContent.classList.remove('hidden');
                uploadArea.classList.remove('file-selected');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const API_URL = "{{ route('scan.scan') }}";
            const scanForm = document.getElementById('scan-form');
            const fileInput = document.getElementById('file');
            const scanButton = document.getElementById('scan-sampah-btn');

            scanForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!fileInput.files.length) {
                    alert('Silakan pilih gambar terlebih dahulu!');
                    return;
                }

                setBusy(true);

                const formData = new FormData(scanForm);
                fetch(API_URL, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal memproses scan. Silakan coba lagi.');
                        }
                        return response.json();
                    })
                    .then(data => showScanResult(data))
                    .catch(err => alert(err.message))
                    .finally(() => setBusy(false));
            });

            function setBusy(state) {
                scanButton.disabled = state;
                scanButton.textContent = state ? 'Menganalisis...' : 'Scan Sampah';
            }

            function showScanResult(data) {
                const resultContainer = document.getElementById('scan-result');
                document.getElementById('result-image').src = document.getElementById('image-preview').src;
                document.getElementById('scan-title').textContent = data.label || 'Tidak Diketahui';


                const descEl = document.getElementById('scan-desc');
                if (data.description) {
                    descEl.textContent = data.description;
                    descEl.classList.remove('hidden');
                } else {
                    descEl.classList.add('hidden');
                }

                fillList('handling-tips', data.suggest|| []);
                fillList('recycling-tips', data.recycling || []);

                resultContainer.classList.remove('hidden');
                resultContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            function fillList(id, items) {
                const list = document.getElementById(id);
                list.innerHTML = '';
                (items || []).forEach(text => {
                    const li = document.createElement('li');
                    li.textContent = text;
                    list.appendChild(li);
                });
            }
        });
    </script>
@endpush
