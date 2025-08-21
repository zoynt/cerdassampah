@extends('layouts.landing')

@section('content')

    <div class="report-container">
        <h1 class="report-title">Laporan TPS Liar</h1>

        <form action="#" method="POST" class="report-form">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama Anda">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email Anda">
                </div>
            </div>

            <div class="form-group">
                <label>Peta</label>
                <div id="report-map"></div>
            </div>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat"
                    placeholder="Geser pin di peta atau ketik alamat di sini">
            </div>

            <div class="form-group">
                <label for="bukti">Unggah Bukti Pendukung</label>
                <div class="file-upload-box" id="file-upload-box">
                    <div class="file-upload-placeholder">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14 12L12.9625 10.75C12.4219 10.125 11.5781 10.125 11.0375 10.75L10 12M12 2C6.475 2 2 6.475 2 12C2 17.525 6.475 22 12 22C17.525 22 22 17.525 22 12C22 6.475 17.525 2 12 2ZM8.00001 15.5C7.9992 16.3288 7.32801 17.0002 6.49923 17C5.67045 16.9998 5.00021 16.3286 5 15.5C5.00021 14.6712 5.67141 13.9998 6.50019 14C7.32897 14.0002 7.99981 14.6714 8.00001 15.5ZM19 18C18.4477 18 18 17.5523 18 17C18 14.7909 16.2091 13 14 13C13.8436 13 13.6908 13.0183 13.5421 13.0528L12.5 11.75C12.2344 11.4375 11.7656 11.4375 11.5 11.75L10.4579 13.0528C10.3092 13.0183 10.1564 13 10 13C7.79086 13 6 14.7909 6 17C6 17.5523 5.55228 18 5 18C4.44772 18 4 17.5523 4 17C4 13.6863 6.68629 11 10 11C10.3703 11 10.7291 11.0537 11.0703 11.1545L11.75 10.25C11.9167 10.0417 12.0833 10.0417 12.25 10.25L12.9297 11.1545C13.2709 11.0537 13.6297 11 14 11C17.3137 11 20 13.6863 20 17C20 17.5523 19.5523 18 19 18Z"
                                fill="#888" />
                        </svg>
                        <span>Unggah foto di sini</span>
                    </div>
                </div>
                <input type="file" id="bukti-input" name="bukti" multiple style="display: none;">
            </div>

            <button type="submit" class="btn btn-secondary btn-submit">Kirim Laporan</button>
        </form>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/laporan.js') }}"></script>
@endpush
