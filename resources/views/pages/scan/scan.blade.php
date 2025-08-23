@extends('layouts.landing')

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

        <input type="file" id="file" name="image" class="hidden" accept=".png, .jpg, .jpeg" onchange="displayFileName()">
    </div>

    <div class="mt-6">
<button id="scan-sampah-btn" type="submit" class="w-full py-3 text-white text-lg bg-green-600 font-semibold rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
    Scan Sampah
</button>


    </div>
</form>

</div>

<div id="scan-result" class="scan-card hidden" data-aos="fade-up">
  <h2 class="scan-card__title text-center">Hasil Scan</h2>

  <div class="scan-card__grid">
    <div class="scan-card__photo">
      <img id="result-image" src="" alt="Hasil Scan">
    </div>

    <div class="scan-card__info text-left">  <!-- tambahkan text-left -->
      <div class="mb-4">
        <span class="badge badge--solid" id="scan-title">—</span>
      </div>

      <p class="scan-card__type text-left"> <!-- tambahkan text-left -->
        <span class="font-semibold">Jenis Sampah</span>
        <span class="badge badge--soft" id="scan-type">—</span>
      </p>

      <p id="scan-desc" class="scan-card__desc hidden"></p>

      <div class="scan-card__section mb-4">
        <span class="section-chip">Saran Penanganan :</span>
        <ol id="handling-tips" class="scan-card__list list-decimal pl-5 text-left"></ol>
      </div>

      <div class="scan-card__section mb-4">
        <span class="section-chip">Daur ulang :</span>
        <ol id="recycling-tips" class="scan-card__list list-decimal pl-5 text-left"></ol>
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

    /* Additional adjustments for spacing and responsiveness */
    @media (max-width: 768px) {
        .flex-col-reverse {
            flex-direction: column-reverse;
        }
        .lg\:py-32 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
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


/* Menambahkan tinggi untuk kotak upload agar gambar lebih leluasa */
#upload-area {
display: flex;
align-items: center;
justify-content: center;
border: 2px dashed #D1D5DB;
border-radius: 10px;
transition: border-color 0.3s ease-in-out;
overflow: hidden; /* Menjaga gambar tetap berada dalam batas kotak */
height: 300px; /* Meningkatkan tinggi area upload untuk memberi lebih banyak ruang */
}

/* Setelah file dipilih, border menjadi hijau */
#upload-area.file-selected {
border-color: #38A169; /* Hijau */
}

/* Menyembunyikan teks dan ikon jika file terpilih */
#upload-area.file-selected #upload-text,
#upload-area.file-selected #upload-icon {
display: none;
}

/* Ketika file terpilih, tampilkan nama file di dalam area border */
#upload-area.file-selected #file-name {
display: block;
font-size: 14px;
color: #4A5568;
}

/* Gambar yang diunggah akan muncul di dalam kotak upload, pastikan gambar mengisi seluruh kotak upload */
#upload-area.file-selected #uploaded-image {
display: block;
margin-top: 10px;
max-width: 100%;   /* Mengatur gambar agar lebar maksimal */
height: 100%;      /* Gambar mengisi tinggi kotak */
object-fit: cover; /* Agar gambar menyesuaikan dengan area tanpa keluar batas */
}

/* Menyembunyikan teks nama file setelah file terpilih, namun gambar tetap muncul */
#file-name {
display: none; /* Menyembunyikan teks nama file */
}

/* Menampilkan gambar setelah di-upload */
#uploaded-image {
display: block;
width: 100%;
height: auto;
border-radius: 10px;  /* Pastikan gambar tidak keluar border */
}

#scan-result {
    /* Hapus display: none */
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 20px;
}


#scan-result img {
max-width: 100px;
height: auto;
border-radius: 10px;
}

#scan-result h2 {
font-size: 1.8rem;
font-weight: bold;
color: #333;
}

#scan-result h3 {
font-size: 1.5rem;
color: #333;
}

#scan-result p {
font-size: 1rem;
color: #666;
}

#scan-result ul {
list-style-type: disc;
padding-left: 20px;
color: #555;
}



    /* For the border and dashed style on file upload */
    .border-2 {
        border-width: 2px;
    }

    .border-dashed {
        border-style: dashed;
    }

    .border-gray-300 {
        border-color: #D1D5DB;
    }


    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #f4f4f9;
    }

    .container {
        width: 90%;
        margin: 0 auto;
    }

    .header {
        background-color: #4CAF50;
        padding: 20px;
        color: white;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .section {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 48%;
        padding: 20px;
        margin-bottom: 20px;
    }

    .section h2 {
        color: #4CAF50;
    }

    .section p {
        font-size: 16px;
        line-height: 1.6;
        color: #333;
    }

    .section img {
        width: 100%;
        max-width: 200px;
        margin-top: 20px;
        border-radius: 8px;
    }

    .section:last-child {
        margin-right: 0;
    }

    .section img {
        margin-left: auto;
        margin-right: auto;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .content {
            flex-direction: column;
        }

        .section {
            width: 100%;
            margin-bottom: 20px;
        }

        .header {
            font-size: 18px;
        }

        .section img {
            max-width: 150px;
        }
    }

    @media (max-width: 480px) {
        .header {
            font-size: 16px;
        }

        .section p {
            font-size: 14px;
        }

        .section img {
            max-width: 120px;
        }
    }

    <!-- Styles khusus card baru (override style lama) -->
<>
/* Pastikan selector diawali #scan-result agar override id lama */
#scan-result.scan-card{
  background:#5E936C;
  border-radius:16px;
  box-shadow:0 8px 24px rgba(0,0,0,.15);
  padding:24px;
  color:#fff;
  margin-top:2rem;
}
#scan-result .scan-card__title{
  text-align:center;
  font-weight:800;
  font-size:1.75rem;
  margin:0 0 16px 0;
}
#scan-result .scan-card__grid{
  display:grid;
  grid-template-columns: 1fr 1.6fr;
  gap:24px;
  align-items:stretch;
}
#scan-result .scan-card__photo{
  background:#fff;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:16px;
}
#scan-result .scan-card__photo img{
  width:100%;
  max-width:360px;
  aspect-ratio:1/1;
  object-fit:contain;
  border-radius:12px;
}
#scan-result .scan-card__info{
  background:#fff;
  color:#374151;
  border-radius:16px;
  padding:20px 24px;
}
#scan-result .badge{
  display:inline-block;
  border-radius:8px;
  padding:8px 14px;
  font-weight:700;
  line-height:1;
}
#scan-result .badge--solid{ background:#5E936C; color:#fff; }
#scan-result .badge--soft{ background:#E6F4EA; color:#2E6141; padding:6px 12px; }

#scan-result .scan-card__type{ margin:10px 0 14px; }
#scan-result .scan-card__desc{ color:#374151; line-height:1.6; margin:0 0 12px 0; }

#scan-result .section-chip{
  display:inline-block;
  background:#5E936C;
  color:#fff;
  border-radius:8px;
  padding:6px 12px;
  font-weight:700;
}
#scan-result .scan-card__list{
  margin:10px 0 0 22px;
  color:#374151;
  list-style:decimal;
}
#scan-result .scan-card__list li{ margin:4px 0; }

@media (max-width: 1024px){
  #scan-result .scan-card__grid{ grid-template-columns: 1fr; }
  #scan-result .scan-card__photo img{ max-width:100%; }
}
</style>



<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const MOCK_MODE = @json(app()->environment(['local','testing'])); // true di local/testing
  const API_URL   = @json(route('scan.scan')); // endpoint nyata untuk production

  const scanForm      = document.getElementById('scan-form');
  const fileInput     = document.getElementById('file');
  const uploadedImage = document.getElementById('uploaded-image');
  const uploadArea    = document.getElementById('upload-area');
  const uploadText    = document.getElementById('upload-text');
  const uploadIcon    = document.getElementById('upload-icon');
  const scanButton    = document.getElementById('scan-sampah-btn');

  // Preview gambar saat dipilih
  fileInput.addEventListener('change', function() {
    if (fileInput.files.length > 0) {
      const reader = new FileReader();
      reader.onload = function (e) {
        uploadedImage.src = e.target.result;
        uploadedImage.classList.remove('hidden');
        uploadText.classList.add('hidden');
        uploadIcon.classList.add('hidden');
      };
      reader.readAsDataURL(fileInput.files[0]);
      uploadArea.classList.add('border-green-500', 'file-selected');
    }
  });

  // Klik Scan
  scanButton.addEventListener('click', function (e) {
    e.preventDefault();
    if (!fileInput.files.length) {
      alert('Silakan pilih gambar terlebih dahulu!');
      return;
    }

    setBusy(true);

    // --- Jalur nyata (production) ---
    const formData = new FormData(scanForm);
    fetch(API_URL, {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
      },
      credentials: 'same-origin'
    })
    .then(async (res) => {
      if (!res.ok) {
        const text = await res.text().catch(() => '');
        throw new Error(text || ('HTTP ' + res.status));
      }
      const contentType = res.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
        throw new Error('Response bukan JSON');
      }
      return res.json();
    })
    .then(data => showScanResult(data)) // Menampilkan hasil dari server
    .catch(err => alert('Gagal memproses scan: ' + err.message))
    .finally(() => setBusy(false));
  });

  function setBusy(state) {
    scanButton.disabled = state;
    scanButton.textContent = state ? 'Menganalisis...' : 'Scan Sampah';
  }

  // ---- Render hasil (mendukung data nyata) ----
  function showScanResult(data) {
    const resultContainer = document.getElementById('scan-result');

    document.getElementById('result-image').src =
      data.imageUrl || uploadedImage.src || '';

    document.getElementById('scan-title').textContent =
      data.label || 'Tidak diketahui';

    document.getElementById('scan-type').textContent =
      data.description || data.type || '';

    // === Deskripsi panjang (opsional) ===
    const descEl = document.getElementById('scan-desc');
    const longDesc = data.description || data.longDescription || '';
    if (descEl) {
      if (longDesc) {
        descEl.textContent = longDesc;
        descEl.classList.remove('hidden');
      } else {
        descEl.classList.add('hidden');
      }
    }

    fillList('handling-tips', data.handlingTips || data.handling || []);
    fillList('recycling-tips', data.recyclingTips || data.recycling || []);

    resultContainer.classList.remove('hidden');
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function fillList(id, items) {
    const ul = document.getElementById(id);
    ul.innerHTML = '';
    (items || []).forEach(text => {
      const li = document.createElement('li');
      li.textContent = text;
      ul.appendChild(li);
    });
  }
});

// ================== POPUP SCRIPT (tidak diubah) ==================
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

    function showStep(step) {
      steps.forEach((el, i) => {
        el.classList.toggle('active', i === step);
      });
      paginationNumbers.forEach((num, i) => {
        num.classList.toggle('active', i === step);
      });
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
  // ================== END POPUP SCRIPT ==================
</script>
@endsection