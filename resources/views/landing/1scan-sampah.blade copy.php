@extends('layouts.app')

@section('title', 'Scan Sampah - CerdasSampah.id')

@section('content')



    <section class="hero-section">
        <div class="hero-background-container">
            <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" class="hero-wave">
                <defs>
                    <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color: #93DA97;" />
                        <stop offset="39%" style="stop-color: #60CB83;" />
                        <stop offset="100%" style="stop-color: #5E936C;" />
                    </linearGradient>
                </defs>
                <path fill="url(#waveGradient)"
                    d="M0,224L48,229.3C96,235,192,245,288,250.7C384,256,480,256,576,229.3C672,203,768,149,864,138.7C960,128,1056,160,1152,165.3C1248,171,1344,149,1392,138.7L1440,128L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
                </path>
            </svg>
        </div>
        <div class="container hero-content">
            <h1 class="page-title">Scan Sampah</h1>
        </div>
    </section>

    <div class="container content-wrapper">
        <div class="scan-section">
            <div class="image-preview-box" id="image-preview-box">
                <div id="placeholder-content">
                    <svg width="100" height="100" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
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

    <section class="steps-section">
        <div class="container">
            <h2 class="steps-title">Langkah Mudah Menggunakan Fitur Scan</h2>
            <p class="steps-subtitle">Unggah foto sampah, dalam tiga langkah sederhana, Anda dapat mengetahui apakah sampah
                tersebut tergolong organik, anorganik, atau berbahaya.</p>
            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number-wrapper">
                        <div class="step-number">1</div>
                    </div>
                    <div class="step-content">
                        <h3>Unggah Gambar Sampah</h3>
                        <p>Ambil foto sampah menggunakan kamera langsung dari aplikasi atau unggah gambar dari galeri.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number-wrapper">
                        <div class="step-number">2</div>
                    </div>
                    <div class="step-content">
                        <h3>Sistem Menganalisis Gambar</h3>
                        <p>Sistem kami akan memproses gambar dan mengenali jenis sampah berdasarkan pola visual.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number-wrapper">
                        <div class="step-number">3</div>
                    </div>
                    <div class="step-content">
                        <h3>Hasil Analisis Ditampilkan</h3>
                        <p>Hasil klasifikasi akan muncul secara otomatis (Organik, anorganik dan berbahaya B3)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection



@push('scripts')
    <script src="{{ asset('js/scan.js') }}"></script>
@endpush
