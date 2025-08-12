@extends('layouts.app')

@section('title', 'Lokasi TPS - CerdasSampah.id')

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
            <h1 class="page-title">Lokasi TPS Kota Banjarmasin</h1>
        </div>
    </section>

    <div class="container content-wrapper">
        <div id="map"></div>

        <div class="tps-summary">
            <div class="summary-box">
                <h3>TPS & TPS-T</h3>
                <p>10</p>
            </div>
            <div class="summary-box">
                <h3>TPS Liar</h3>
                <p>10</p>
            </div>
        </div>

        <div class="tps-list-container">
            <div class="tps-list-item" data-lat="-3.310000" data-lng="114.595000">
                <p>Jl. Pangeran Hidayatullah, Depan Kantor Pariwisata, Kecamatan Banjarmasin Timur, Kelurahan Benua Anyar
                </p>
                <span class="status resmi">Resmi</span>
            </div>
            <div class="tps-list-item" data-lat="-3.325500" data-lng="114.600111">
                <p>Jl. A. Yani km 6, Samping Komplek Sari Indah, Kecamatan Banjarmasin Selatan</p>
                <span class="status liar">Liar</span>
            </div>
            <div class="tps-list-item" data-lat="-3.317694" data-lng="114.580111">
                <p>Jl. Belitung Darat, Kecamatan Banjarmasin Barat, Kelurahan Kuin Cerucuk</p>
                <span class="status resmi">Resmi</span>
            </div>
            <div class="tps-list-item hidden" data-lat="-3.331694" data-lng="114.592111">
                <p>Area Pasar Bawang, Jl. Veteran, Kecamatan Banjarmasin Tengah</p>
                <span class="status liar">Liar</span>
            </div>
            <div class="tps-list-item hidden" data-lat="-3.298990" data-lng="114.591330">
                <p>Jl. Sultan Adam, Komplek Mandiri, Banjarmasin Utara</p>
                <span class="status resmi">Resmi</span>
            </div>
        </div>
        <div class="show-more-container">
            <a href="#" class="show-more-btn">Tampilkan lebih banyak</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/map.js') }}"></script>
@endpush
