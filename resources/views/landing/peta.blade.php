@push('styles')
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-sA+e2PskCwraOsB+K3TvKjFBv6JkGtD9rBV2RNz9EUQ="
  crossorigin=""
/>
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
</style>
@endpush

<section id="peta" class="relative z-10 py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-4">
      <div class="hidden lg:block lg:col-span-3"></div>
      <div class="flex justify-end">
        <h2 class="text-xl font-bold text-green-900 tracking-wide mb-2 border-b-2 border-green-700 w-fit" data-aos="fade-down">
            Peta TPS, TPS-T3R & TPS Liar
        </h2>
    </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
      <div class="lg:col-span-3 border-4 border-green-400 rounded-xl overflow-hidden shadow-md mb-6" data-aos="zoom-in">
        <div id="leaflet-map" class="w-full h-[400px] sm:h-[450px] md:h-[500px]"></div>
      </div>
      <div class="lg:col-span-1 text-gray-800 mt-6 lg:mt-0 space-y-5" data-aos="fade-left">
        <h3 class="text-xl md:text-2xl leading-tight align-middle mb-4">
          Temukan Lokasi
          <span class="highlight-animate">TPS, TPS-T3R & TPS Liar</span>
          di Sekitarmu
        </h3>
        <p class="text-gray-600 !mt-0 mb-4 text-sm md:text-base leading-normal">
          Ada lebih dari 10 titik TPS, TPS-T3R & TPS Liar terdaftar di Website CerdasSampah.id!
        </p>
        <div class="space-y-4">
          <div class="bg-green-500 text-center text-white py-4 rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="0">
            <p class="font-semibold text-sm">TPS</p>
            <p class="text-2xl font-bold">00</p>
          </div>
          <div class="bg-blue-500 text-center text-white py-4 rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="50">
            <p class="font-semibold text-sm">TPS-T & TPS 3R</p>
            <p class="text-2xl font-bold">00</p>
          </div>
          <div class="bg-red-500 text-center text-white py-4 rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <p class="font-semibold text-sm">TPS Liar</p>
            <p class="text-2xl font-bold">00</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-u5A0s+GUG1wzFkX6wDfQdC7p6D8uw9sXjHf9jQbTzlc=" crossorigin=""></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();

  document.addEventListener("DOMContentLoaded", function () {
    const targets = document.querySelectorAll('.highlight-animate');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
        } else {
          entry.target.classList.remove('active');
        }
      });
    }, {
      threshold: 0.6
    });
    targets.forEach(target => observer.observe(target));

    const map = L.map('leaflet-map').setView([-3.2910732404474583, 114.59812756610306], 13);

    const baseOSM = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors', maxZoom: 18,
    });
    const baseESRI = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      attribution: 'Tiles © Esri', maxZoom: 18,
    });
    const baseMapTiler = L.tileLayer('https://api.maptiler.com/maps/satellite/{z}/{x}/{y}.jpg?key=8fxAAHSSmNgIETRazciC', {
      attribution: '© MapTiler © OpenStreetMap contributors', maxZoom: 18,
    });

    baseOSM.addTo(map);

    const layerTPS = L.layerGroup();
    const layerTPST3R = L.layerGroup();
    const layerTPSLiar = L.layerGroup();

    const dummyTPS = [
      { lat: -3.2910732404474583, lng: 114.59812756610306, name: 'TPS' },
      { lat: -3.291075, lng: 114.6, name: 'TPS-T3R' },
      { lat: -3.291072, lng: 114.58, name: 'TPS Liar' },
    ];

    dummyTPS.forEach(loc => {
      let marker;
      if (loc.name.includes("Liar")) {
        marker = L.marker([loc.lat, loc.lng], { icon: getColoredIcon("red") }).bindPopup(`<b>${loc.name}</b>`);
        marker.addTo(layerTPSLiar);
      } else if (loc.name.includes("T3R")) {
        marker = L.marker([loc.lat, loc.lng], { icon: getColoredIcon("blue") }).bindPopup(`<b>${loc.name}</b>`);
        marker.addTo(layerTPST3R);
      } else {
        marker = L.marker([loc.lat, loc.lng], { icon: getColoredIcon("green") }).bindPopup(`<b>${loc.name}</b>`);
        marker.addTo(layerTPS);
      }
    });

    layerTPS.addTo(map);
    layerTPST3R.addTo(map);
    layerTPSLiar.addTo(map);

    const baseMaps = {
      "Citra Biasa (OSM)": baseOSM,
      "Citra Satelit (ESRI)": baseESRI,
      "Citra Satelit (MapTiler)": baseMapTiler
    };

    const overlayMaps = {
      "TPS": layerTPS,
      "TPS-T3R": layerTPST3R,
      "TPS Liar": layerTPSLiar
    };

    L.control.layers(baseMaps, overlayMaps, { collapsed: true }).addTo(map);

    // Disable zoom by default
    map.scrollWheelZoom.disable();

    // Track Ctrl key press state
    let ctrlPressed = false;

    // Detect when the Ctrl key is pressed
    document.addEventListener('keydown', function (e) {
      if (e.ctrlKey) {
        ctrlPressed = true;
      }
    });

    // Detect when the Ctrl key is released
    document.addEventListener('keyup', function (e) {
      if (!e.ctrlKey) {
        ctrlPressed = false;
      }
    });

    // Enable zoom only when Ctrl key is pressed and the map is clicked
    map.on('mousedown', function (e) {
      if (ctrlPressed) {
        map.scrollWheelZoom.enable(); // Enable zooming when Ctrl is pressed and the map is clicked
      } else {
        map.scrollWheelZoom.disable(); // Disable zooming if Ctrl is not pressed
      }
    });

    map.on('mouseover', function () {
      if (!ctrlPressed) {
        map.scrollWheelZoom.disable(); // Disable zooming if Ctrl is not pressed
      }
    });

    map.on('mouseout', function () {
      if (!ctrlPressed) {
        map.scrollWheelZoom.disable(); // Disable zooming if Ctrl is not pressed
      }
    });

    // Function to create colored icons for markers
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
  });
</script>

@endpush
