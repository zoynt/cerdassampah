<section id="smart" class="py-16 bg-white scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 mb-8">
            <div class="hidden lg:block lg:col-span-3"></div>
            <div class="flex justify-end">
                <h2 class="text-xl font-bold text-blue-900 tracking-wide mb-2 border-b-2 border-blue-700 w-fit"
                    data-aos="fade-down">
                    Smart City
                </h2>
            </div>
        </div>

        <div x-data="{ activePillar: 'environment' }" data-aos="fade-up" data-aos-delay="200">
            
            @php
                // pilar 
                $pillars = [
                    ['key' => 'governance', 'name' => 'Smart Governance', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-6 h-6" fill="none"><path d="M7.743 21.8H11.228V44.2H16.457V25H21.686V44.2H26.914V25H32.144V44.2H37.373V21.8H40.857C41.82 21.8 42.601 21.084 42.601 20.2C42.601 19.666 42.313 19.196 41.874 18.906L41.877 18.903L41.851 18.888L41.806 18.861L25.171 10.252V7.73C28.243 9.142 30.772 6.71 34.756 8.172V2.601C30.77 1.139 28.242 3.569 25.171 2.158V1.8C25.171 1.357 24.782 1 24.3 1C23.818 1 23.43 1.357 23.43 1.8V10.252L6.795 18.859L6.75 18.886L6.725 18.903V18.906C6.288 19.196 6.001 19.667 6.001 20.2C6 21.084 6.781 21.8 7.743 21.8ZM9.485 45.801L6 49H42.602L39.115 45.801H9.485Z" fill="currentColor"/></svg>'],
                    ['key' => 'branding', 'name' => 'Smart Branding', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-6 h-6" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M25 3.125C20.4416 3.125 16.0699 4.93582 12.8466 8.1591C9.62332 11.3824 7.8125 15.7541 7.8125 20.3125V23.4375C7.8125 25.0875 8.48438 26.6719 9.19688 27.8937C9.91875 29.1406 10.7875 30.1844 11.3937 30.7938L15.8219 35.2188L17.625 40.625H32.375L34.1781 35.2188L38.6063 30.7938C39.2125 30.1844 40.0813 29.1375 40.8031 27.8937C41.5156 26.6719 42.1875 25.0875 42.1875 23.4375V20.3125C42.1875 15.7541 40.3767 11.3824 37.1534 8.1591C33.9301 4.93582 29.5584 3.125 25 3.125ZM29.2313 16.7313L27.0187 14.5187L21.2281 20.3125L24.3531 23.4375L20.7687 27.0187L22.9813 29.2313L28.7719 23.4375L25.6469 20.3125L29.2313 16.7313Z" fill="currentColor"/><path d="M31.25 46.875V43.75H18.75V46.875H31.25Z" fill="currentColor"/></svg>'],
                    ['key' => 'economy', 'name' => 'Smart Economy', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-6 h-6" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.6583 7.60426L18.8874 5.69385L16.977 7.46676L2.39367 21.0084L5.9395 24.8251L18.6145 13.0543L30.3833 25.7313L32.2228 27.7105L34.1333 25.8001L42.4666 17.4668L45.8333 20.8334L47.9166 18.7501V8.33343H37.4999L35.4166 10.4168L38.7832 13.7834L32.3603 20.2084L20.6583 7.60426ZM10.4166 47.9168V30.2084H2.08325V47.9168H10.4166ZM14.5833 47.9168V20.8334H22.9166V47.9168H14.5833ZM27.0833 33.3334V47.9168H35.4166V33.3334H27.0833ZM39.5833 47.9168V26.0418H47.9166V47.9168H39.5833Z" fill="currentColor"/></svg>'],
                    ['key' => 'environment', 'name' => 'Smart Environment', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-6 h-6" fill="none"><path d="M45.4584 32.125L40.2501 41.1458C39.2292 42.9375 37.3334 43.875 35.4167 43.75H31.2501V47.9167L26.0417 38.5417L31.2501 29.1667V33.3333H37.1251L32.5001 25.3125L41.5209 20.1042L45.2709 26.6042C46.3542 28.2083 46.5001 30.3542 45.4584 32.125ZM19.1876 6.375H29.6042C31.6459 6.375 33.4167 7.5625 34.2709 9.27083L36.3542 12.8958L39.9584 10.8125L34.4584 20L23.7292 20.1875L27.3334 18.1042L24.3959 13L19.7917 21.0208L10.7501 15.8125L14.5001 9.3125C15.3542 7.58333 17.1251 6.375 19.1876 6.375ZM10.5209 41.1667L5.31258 32.1458C4.29175 30.375 4.43758 28.25 5.50008 26.6458L7.58341 23.0417L3.97925 20.9583L14.6876 21.125L20.2084 30.3333L16.6042 28.25L13.6667 33.3333H22.9167V43.75H15.4167C14.4407 43.8206 13.4644 43.616 12.599 43.1593C11.7335 42.7026 11.0135 42.0122 10.5209 41.1667Z" fill="currentColor"/></svg>'],
                    ['key' => 'living', 'name' => 'Smart Living', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-6 h-6" fill="none"><path d="M25.0001 6.25L4.16675 25H10.4167V41.6667H39.5834V25H45.8334L25.0001 6.25ZM25.0001 17.7083C29.8751 17.7083 34.2917 19.6458 37.5001 22.7917L35.0001 25.25C32.3334 22.6293 28.7389 21.169 25.0001 21.1875C21.0834 21.1875 17.5417 22.7292 15.0001 25.25L12.5001 22.7917C15.8376 19.5216 20.3276 17.6956 25.0001 17.7083ZM25.0001 24.6458C27.9167 24.6458 30.5626 25.8125 32.5001 27.7083L30.0001 30.1458C28.6651 28.8393 26.868 28.113 25.0001 28.125C23.0417 28.125 21.2709 28.8958 20.0001 30.1458L17.5001 27.7083C19.5013 25.7428 22.1951 24.6429 25.0001 24.6458ZM25.0001 31.6042C26.9584 31.6042 28.5417 33.1458 28.5417 35.0625C28.5417 36.9792 26.9584 38.5417 25.0001 38.5417C23.0417 38.5417 21.4584 36.9792 21.4584 35.0625C21.4584 33.1458 23.0417 31.6042 25.0001 31.6042Z" fill="currentColor"/></svg>'],
                    ['key' => 'society', 'name' => 'Smart Society', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-6 h-6" fill="none"><path d="M24.9999 5C23.0108 5 21.1031 5.79018 19.6966 7.1967C18.2901 8.60322 17.4999 10.5109 17.4999 12.5C17.4999 14.4891 18.2901 16.3968 19.6966 17.8033C21.1031 19.2098 23.0108 20 24.9999 20C26.989 20 28.8967 19.2098 30.3032 17.8033C31.7097 16.3968 32.4999 14.4891 32.4999 12.5C32.4999 10.5109 31.7097 8.60322 30.3032 7.1967C28.8967 5.79018 26.989 5 24.9999 5ZM12.6324 24.99C12.5474 25.3967 12.5049 25.8167 12.5049 26.25V35C12.5033 36.815 12.8977 38.6085 13.6607 40.2553C14.4236 41.9022 15.5368 43.3627 16.9224 44.535L16.4949 44.66C15.2263 45.0003 13.9032 45.0873 12.6009 44.9162C11.2987 44.745 10.043 44.319 8.90541 43.6625C7.76786 43.0059 6.77078 42.1318 5.97112 41.0898C5.17146 40.0479 4.58489 38.8587 4.2449 37.59L2.6274 31.5525C2.50001 31.0768 2.46756 30.5806 2.53191 30.0924C2.59627 29.6041 2.75616 29.1333 3.00247 28.7069C3.24878 28.2804 3.57668 27.9067 3.96744 27.6069C4.35821 27.3072 4.80418 27.0874 5.2799 26.96L12.6324 24.99ZM33.0874 44.535C34.473 43.3627 35.5862 41.9022 36.3491 40.2553C37.1121 38.6085 37.5065 36.815 37.5049 35V26.25C37.5032 25.8167 37.4616 25.3967 37.3799 24.99L44.7299 26.96C45.6902 27.2178 46.5088 27.8464 47.0058 28.7076C47.5027 29.5688 47.6373 30.5921 47.3799 31.5525L45.7624 37.59C45.4127 38.8946 44.8023 40.1148 43.9681 41.177C43.1339 42.2392 42.0932 43.1215 40.9087 43.7705C39.7242 44.4195 38.4205 44.8218 37.0763 44.9532C35.7321 45.0846 34.3751 44.9424 33.0874 44.535ZM41.2499 10C39.5923 10 38.0026 10.6585 36.8305 11.8306C35.6584 13.0027 34.9999 14.5924 34.9999 16.25C34.9999 17.9076 35.6584 19.4973 36.8305 20.6694C38.0026 21.8415 39.5923 22.5 41.2499 22.5C42.9075 22.5 44.4972 21.8415 45.6693 20.6694C46.8414 19.4973 47.4999 17.9076 47.4999 16.25C47.4999 14.5924 46.8414 13.0027 45.6693 11.8306C44.4972 10.6585 42.9075 10 41.2499 10ZM8.7499 10C7.0923 10 5.50259 10.6585 4.33049 11.8306C3.15838 13.0027 2.4999 14.5924 2.4999 16.25C2.4999 17.9076 3.15838 19.4973 4.33049 20.6694C5.50259 21.8415 7.0923 22.5 8.7499 22.5C10.4075 22.5 11.9972 21.8415 13.1693 20.6694C14.3414 19.4973 14.9999 17.9076 14.9999 16.25C14.9999 14.5924 14.3414 13.0027 13.1693 11.8306C11.9972 10.6585 10.4075 10 8.7499 10ZM18.7499 22.5C17.7553 22.5 16.8015 22.8951 16.0983 23.5983C15.395 24.3016 14.9999 25.2554 14.9999 26.25V35C14.9999 37.6522 16.0535 40.1957 17.9288 42.0711C19.8042 43.9464 22.3477 45 24.9999 45C27.6521 45 30.1956 43.9464 32.071 42.0711C33.9463 40.1957 34.9999 37.6522 34.9999 35V26.25C34.9999 25.2554 34.6048 24.3016 33.9016 23.5983C33.1983 22.8951 32.2445 22.5 31.2499 22.5H18.7499Z" fill="currentColor"/></svg>'],
                ];
                $featureIcon = '<span class="flex-shrink-0 w-6 h-6 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                    </svg>
                                </span>';
            @endphp

            {{-- Navigasi Pilar--}}
            <div class="bg-blue-50 p-3 rounded-xl shadow-inner md:mb-12"> 
                {{-- Tampilan MOBILE--}}
                <div class="grid grid-cols-6 gap-2 md:hidden">
                    @foreach ($pillars as $pillar)
                        <button
                            @click="activePillar = '{{ $pillar['key'] }}'"
                            :class="activePillar === '{{ $pillar['key'] }}' ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-blue-800'"
                            class="flex items-center justify-center p-3 rounded-lg transition-all duration-300">
                            {!! $pillar['icon'] !!}
                        </button>
                    @endforeach
                </div>
                {{-- Tampilan DESKTOP--}}
                <div class="hidden md:grid md:grid-cols-6 gap-2 md:min-w-full">
                    @foreach ($pillars as $pillar)
                        <button
                            @click="activePillar = '{{ $pillar['key'] }}'"
                            :class="activePillar === '{{ $pillar['key'] }}' ? 'bg-blue-600 text-white shadow-lg rounded-xl -m-1 py-4' : 'bg-transparent text-blue-800 hover:bg-blue-100 rounded-lg py-3'"
                            class="flex items-center justify-center gap-2 px-4 font-semibold transition-all duration-300 text-sm md:text-base">
                            <span>{{ $pillar['name'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>


            <div class="w-full mt-8 md:mt-12">
                
                {{-- KONTEN 1: SMART ENVIRONMENT --}}
                <div x-show="activePillar === 'environment'" x-transition>
                    <div class="bg-blue-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md mb-8 text-center" data-aos="fade-down">
                        Smart Environment
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                        <div class="w-full h-auto lg:h-96 bg-white rounded-2xl shadow-xl flex items-center justify-center" data-aos="fade-right">
                            <img src="{{ asset('img/smart-environment.jpg') }}" alt="Smart Environment" class="w-full h-64 lg:h-full object-cover rounded-xl">
                        </div>
                        <div class="w-full h-auto lg:h-96 flex flex-col justify-center" data-aos="fade-left">
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Smart Environment bertujuan menciptakan tata kelola lingkungan yang berkelanjutan melalui teknologi, mulai dari pengelolaan sampah, pengendalian polusi, hingga pelestarian alam demi terciptanya kota yang bersih dan sehat.
                            </p>
                            <a href="{{ route('landing.peta') }}" 
                                class="inline-block bg-blue-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md hover:bg-blue-700 transition-colors mb-6 w-fit">
                                Cerdas Sampah
                            </a>
                            @php
                                $env_items = ['Laporan TPS Liar', 'Lokasi TPS', 'Game Pilah Sampah', 'Scan Sampah', 'Bank Sampah Digital', 'Marketplace Daur Ulang'];
                            @endphp
                            <ul class="grid grid-cols-1 md:grid-cols-2 md:grid-rows-5 md:grid-flow-col gap-y-3 md:gap-x-4 lg:gap-x-6">
                                @foreach ($env_items as $item)
                                <li class="flex items-center gap-3">
                                    {!! $featureIcon !!}
                                    <span class="text-gray-700">{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- KONTEN 2: SMART GOVERNANCE --}}
                <div x-show="activePillar === 'governance'" x-transition style="display: none;"> 
                    <div class="bg-blue-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md mb-8 text-center" data-aos="fade-down">
                        Smart Governance
                    </div>
                    <div class="p-8 text-center">
                        <p class="text-gray-600 mt-2">Smart Governance merupakan tata kelola pemerintahan yang memanfaatkan teknologi informasi untuk meningkatkan transparansi, efisiensi, serta partisipasi masyarakat. Melalui sistem e-Government, layanan publik seperti administrasi kependudukan, perizinan, dan pengaduan masyarakat dapat diakses secara digital, sehingga tercipta pemerintahan yang terbuka dan responsif.</p>
                    </div>
                </div>
                
                {{-- KONTEN 3: SMART BRANDING--}}
                <div x-show="activePillar === 'branding'" x-transition style="display: none;">
                    <div class="bg-blue-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md mb-8 text-center" data-aos="fade-down">
                        Smart Branding
                    </div>
                    <div class="p-8 text-center">
                        <p class="text-gray-600 mt-2">Smart Branding berfokus pada pembangunan citra positif dan keunggulan daerah melalui inovasi dan promosi potensi lokal. Kota dengan branding yang kuat mampu menarik wisatawan, investor, dan meningkatkan kebanggaan warganya. Misalnya, dengan mengoptimalkan promosi destinasi wisata dan identitas khas daerah melalui media digital.</p>
                    </div>
                </div>

                {{-- KONTEN 4: SMART ECONOMY--}}
                <div x-show="activePillar === 'economy'" x-transition style="display: none;">
                    <div class="bg-blue-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md mb-8 text-center" data-aos="fade-down">
                        Smart Economy
                    </div>
                    <div class="p-8 text-center">
                        <p class="text-gray-600 mt-2">Smart Economy mengacu pada penerapan teknologi dalam aktivitas ekonomi agar lebih efisien dan berdaya saing. Transformasi digital mendorong berkembangnya UMKM berbasis e-commerce, sistem pembayaran non-tunai, dan inovasi bisnis seperti startup dan fintech, yang pada akhirnya meningkatkan produktivitas serta membuka lapangan kerja baru.</p>
                    </div>
                </div>

                {{-- KONTEN 5: SMART LIVING--}}
                <div x-show="activePillar === 'living'" x-transition style="display: none;">
                    <div class="bg-blue-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md mb-8 text-center" data-aos="fade-down">
                        Smart Living
                    </div>
                   <div class="p-8 text-center">
                        <p class="text-gray-600 mt-2">Smart Living berfokus pada peningkatan kualitas hidup warga melalui pemanfaatan teknologi di berbagai aspek, seperti layanan kesehatan digital, sistem pendidikan berbasis e-learning, serta transportasi publik yang terintegrasi. Dengan begitu, kehidupan masyarakat menjadi lebih nyaman, aman, dan sejahtera.</p>
                    </div>
                </div>

                {{-- KONTEN 6: SMART SOCIETY--}}
                <div x-show="activePillar === 'society'" x-transition style="display: none;">
                    <div class="bg-blue-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md mb-8 text-center" data-aos="fade-down">
                        Smart Society
                    </div>
                    <div class="p-8 text-center">
                        <p class="text-gray-600 mt-2">Smart Society menekankan pada pemberdayaan masyarakat agar mampu beradaptasi dengan perkembangan teknologi. Masyarakat didorong untuk aktif berpartisipasi dalam inovasi dan pembangunan kota melalui pelatihan literasi digital, kolaborasi komunitas, serta akses terhadap informasi dan pendidikan. Pilar ini menjadi fondasi penting bagi keberhasilan seluruh elemen Smart City, karena masyarakat yang cerdas dan inklusif merupakan kunci utama terwujudnya kota yang berkelanjutan.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>