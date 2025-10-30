<footer class="bg-sky-50 pt-10 md:pt-16 text-black font-['Poppins'] relative overflow-visible">
    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pb-10 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start gap-5 md:gap-4">
            
            {{-- Logo --}}
            <div class="flex items-center gap-4 flex-wrap justify-center mb-5 md:mb-0">
                <span class="text-xl md:text-3xl font-bold">SmartCity.id</span>
            </div>

            {{-- Kontak --}}
            <div class="flex flex-col gap-4 text-sm w-full md:max-w-md mt-2.5 md:mt-5">
                {{-- Alamat --}}
                <div class="flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned-icon lucide-map-pinned w-7 h-7 flex-shrink-0">
                        <path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/>
                    </svg>
                    <div>
                        <strong class="font-bold block">Alamat</strong>
                        Komplek Andhika, Jl. Sultan Adam No.3, RW.Viaworkspace,<br>
                        Sungai Miai, Banjarmasin Utara, Banjarmasin City,<br>
                        South Kalimantan 70123
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail w-7 h-7 flex-shrink-0">
                        <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/>
                    </svg>
                    <div>
                        <strong class="font-bold block">Email</strong>
                        hello@via.co.id
                    </div>
                </div>
            </div>
        </div>

        {{-- Garis Pembatas --}}
       <div class="mt-[35px] mb-[25px] h-[1px] bg-blue-800"></div>
        
        {{-- Tentang dan Sosial Media --}}
        <div class="text-sm md:text-base leading-relaxed">
            <strong class="font-bold block mb-2">Tentang SmartCity.id</strong>
            <p class="m-0">
                SmartCity.id hadir sebagai solusi digital untuk mendukung enam pilar Smart City, mendorong pelayanan publik yang efisien, partisipatif, dan ramah lingkungan.
            </p>

            {{-- Ikon Sosial --}}
            <div class="mt-5">
                <div class="flex gap-4 md:gap-5 items-center pb-6 md:pb-9">
                    <a href="#" target="_blank" class="text-current no-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-9 h-9">
                            <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17" />
                            <path d="m10 15 5-3-5-3z" />
                        </svg>
                    </a>
                    <a href="#" target="_blank" class="text-current no-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-instagram-icon lucide-instagram w-8 h-8">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="text-center mt-8 text-xs md:text-base">
            © Copyright 2025 <strong class="font-bold">SmartCity.id</strong> – All Rights Reserved.
        </div>
    </div>

    {{-- Wave Background --}}
    <div class="absolute bottom-0 left-0 w-full z-0 leading-none transform origin-bottom" style="transform: scaleY(0.55);">
        <svg class="w-full h-full" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg">
            <path d="M 0,400 L 0,150 C 142.53571428571428,182.42857142857144 285.07142857142856,214.85714285714286 395,215
                C 504.92857142857144,215.14285714285714 582.2499999999999,183.00000000000003 701,180
                C 819.7500000000001,176.99999999999997 979.9285714285716,203.1428571428571 1110,203
                C 1240.0714285714284,202.8571428571429 1340.0357142857142,176.42857142857144 1440,150
                L 1440,400 L 0,400 Z"
                class="fill-white"></path>
        </svg>
    </div>
</footer>