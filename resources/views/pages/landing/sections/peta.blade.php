@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-sA+e2PskCwraOsB+K3TvKjFBv6JkGtD9rBV2RNz9EUQ=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <style>
        .highlight-animate {
            position: relative;
            display: inline-block;
            color: white;
            padding: 0 0.4rem;
            border-radius: 0.25rem;
            background-color: transparent;
            z-index: 0;
            overflow: hidden;
            vertical-align: middle;
        }

        .highlight-animate::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0;
            background-color: #15803d;
            z-index: -1;
            border-radius: 0.25rem;
            transition: width 2.5s ease-out;
        }

        .highlight-animate.active::before {
            width: 100%;
        }

        .leaflet-control-layers-expanded {
            max-height: 400px;
            overflow-y: auto;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            padding: 1px;
        }

        .leaflet-popup-content {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
    </style>
@endpush

<section id="peta" class="relative z-10 py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-4">
            <div class="hidden lg:block lg:col-span-3"></div>
            <div class="flex justify-end">
                <h2 class="text-xl font-bold text-green-900 tracking-wide mb-2 border-b-2 border-green-700 w-fit"
                    data-aos="fade-down">
                    Peta TPS, TPS-T3R & TPS Liar
                </h2>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <div class="lg:col-span-3 border-4 border-green-400 rounded-xl overflow-hidden shadow-md mb-6"
                data-aos="zoom-in">
                <div id="leaflet-map" class="w-full h-[400px] sm:h-[450px] md:h-[500px]"></div>
            </div>
            <div class="lg:col-span-1 text-gray-800 mt-6 lg:mt-0 space-y-5" data-aos="fade-left">
                <h3 class="text-xl md:text-2xl leading-tight align-middle mb-4">
                    Temukan Lokasi
                    <span class="highlight-animate">TPS, TPS-T3R & TPS Liar</span>
                    di Sekitarmu
                </h3>
                <p class="text-gray-600 !mt-0 mb-4 text-sm md:text-base leading-normal">
                    Ada lebih dari 10 titik TPS, TPS-T3R & TPS Liar terdaftar di Website BapilahSampah!
                </p>
                <div class="space-y-4">
                    <div class="bg-green-500 text-center text-white py-4 rounded-lg shadow-sm" data-aos="fade-up"
                        data-aos-delay="0">
                        <p class="font-semibold text-sm">TPS Resmi</p>
                        <p class="text-2xl font-bold">{{ $tpsCount }}</p>
                    </div>
                    <div class="bg-blue-500 text-center text-white py-4 rounded-lg shadow-sm" data-aos="fade-up"
                        data-aos-delay="50">
                        <p class="font-semibold text-sm">TPS-T & TPS 3R</p>
                        <p class="text-2xl font-bold">1</p>
                    </div>
                    <div class="bg-red-500 text-center text-white py-4 rounded-lg shadow-sm" data-aos="fade-up"
                        data-aos-delay="100">
                        <p class="font-semibold text-sm">TPS Liar</p>
                        <p class="text-2xl font-bold">{{ $tpsLiarCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
        document.addEventListener("DOMContentLoaded", function() {

            const targets = document.querySelectorAll('.highlight-animate');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('active');
                });
            }, {
                threshold: 0.6
            });
            targets.forEach(target => observer.observe(target));

            const map = L.map('leaflet-map').setView([-3.291073, 114.598127], 13);
            const baseOSM = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);
            const baseESRI = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles © Esri',
                    maxZoom: 18
                });

            const locations = @json($locations);
            const layerTPS = L.layerGroup().addTo(map);
            const layerTPST3R = L.layerGroup().addTo(map);
            const layerTPSLiar = L.layerGroup().addTo(map);


            locations.forEach(loc => {
                let marker;
                const popupContent = createPopupContent(loc);
                if (loc.status === 'liar') {
                    marker = L.marker([loc.lat, loc.lng], {
                        icon: getColoredIcon("red")
                    }).bindPopup(popupContent);
                    marker.addTo(layerTPSLiar);
                } else {
                    marker = L.marker([loc.lat, loc.lng], {
                        icon: getColoredIcon("green")
                    }).bindPopup(popupContent);
                    marker.addTo(layerTPS);
                }
            });

            const dummyTps3r = {
                name: 'TPS3R Sungai Biuku',
                lat: -3.288112785949744,
                lng: 114.61836269905916,
                status: 'tps-3r',
                address: 'Jl. Lakstarda Tembus Terantang, Sungai Tandipah, Kec. Banjarmasin Utara, Kota Banjarmasin, Kalimantan Selatan',
                description: 'pengelolaan sampah kawasan 3R (Mengurangi, Menggunakan Kembali, Mendaur Ulang).',
                image_url: 'https://placehold.co/600x400/3b82f6/ffffff?text=TPS-3R'
            };
            const dummyPopupContent = createPopupContent(dummyTps3r);
            L.marker([dummyTps3r.lat, dummyTps3r.lng], {
                    icon: getColoredIcon("blue")
                })
                .bindPopup(dummyPopupContent)
                .addTo(layerTPST3R);

            const baseMaps = {
                "OpenStreetMap": baseOSM,
                "Satelit Esri": baseESRI
            };
            const overlayMaps = {
                "TPS": layerTPS,
                "TPS-T3R": layerTPST3R,
                "TPS Liar": layerTPSLiar
            };
            L.control.layers(baseMaps, overlayMaps).addTo(map);


            const allPoints = locations.map(loc => [loc.lat, loc.lng]);
            allPoints.push([dummyTps3r.lat, dummyTps3r.lng]);
            if (allPoints.length > 0) {
                const bounds = L.latLngBounds(allPoints);
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            }


            function createPopupContent(loc) {
                return `<div class="w-64 rounded-lg overflow-hidden shadow-lg bg-white p-0" style="margin:0;">
                            <img class="w-full h-32 object-cover" src="${loc.image_url}" alt="Foto ${loc.name}">
                            <div class="p-3">
                                <div class="font-bold text-base mb-1 text-gray-800">${loc.name}</div>
                                <p class="text-gray-600 text-xs mb-2"><span class="font-semibold">Alamat: </span>${loc.tps_address || 'Tidak ada alamat'}</p>
                                <p class="text-gray-600 text-xs mb-2"><span class="font-semibold">Deskripsi: </span>${loc.description || 'Tidak ada deskripsi'}</p>
                                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold text-white ${getPopupBadgeColor(loc.status)}">
                                    Status: ${loc.status.charAt(0).toUpperCase() + loc.status.slice(1).replace('-',' ')}
                                </span>
                            </div>
                        </div>`;
            }

            function getPopupBadgeColor(status) {
                if (status === 'liar') return 'bg-red-500';
                if (status === 'tps-3r') return 'bg-blue-500';
                return 'bg-green-600';
            }

            function getColoredIcon(color) {
                return L.icon({
                    iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${color}.png`,
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                });
            }

            map.scrollWheelZoom.disable();
            let ctrlPressed = false;
            document.addEventListener('keydown', (e) => {
                if (e.ctrlKey) ctrlPressed = true;
            });
            document.addEventListener('keyup', (e) => {
                if (!e.ctrlKey) ctrlPressed = false;
            });
            map.on('mousedown', () => {
                if (ctrlPressed) map.scrollWheelZoom.enable();
                else map.scrollWheelZoom.disable();
            });
        });
    </script>
@endpush
