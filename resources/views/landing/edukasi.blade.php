@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
@endpush

<section id="edukasi" class="relative z-10 pb-12 bg-green-400">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-1">
      
      <h2 class="text-xl font-bold text-green-900 tracking-wide mb-8 border-b-2 border-green-700 w-fit z-[1]" data-aos="fade-down">
        Video Edukasi
      </h2>
    </div>

    <div class="absolute top-0 left-0 w-full overflow-hidden leading-[0] z-[-999]">
    <svg width="100%" height="100%" id="svg" viewBox="0 0 1440 390" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150"><path d="M 0,400 L 0,150 C 101.81818181818178,165.95215311004785 203.63636363636357,181.90430622009566 299,191 C 394.36363636363643,200.09569377990434 483.27272727272737,202.33492822966508 578,200 C 672.7272727272726,197.66507177033492 773.2727272727273,190.755980861244 878,171 C 982.7272727272727,151.244019138756 1091.6363636363637,118.64114832535886 1186,113 C 1280.3636363636363,107.35885167464114 1360.181818181818,128.67942583732057 1440,150 L 1440,400 L 0,400 Z" stroke="none" stroke-width="0" fill="#ffffff" fill-opacity="1" class="transition-all duration-300 ease-in-out delay-150 path-0" transform="rotate(-180 720 200)"></path></svg>
    </div>
    
    <div class="h-[400px] border-4 border-white rounded-xl overflow-hidden shadow-md mb-6 z-99" data-aos="zoom-in">
        <div id="video-player" class="w-full h-full"></div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-1 items-start text-center" data-aos="fade-up">
      <div class="lg:col-span-1 text-white mt-6 lg:mt-0">
        <h3 class="text-2xl md:text-3xl font-semibold leading-tight mt-2 mb-4">
          Cara Memilah Sampah Rumah Tangga
        </h3>
        <p class="text-white/90 mt-0 mb-8 text-lg md:text-xl leading-normal max-w-4xl mx-auto">
          Pelajari cara memilah dan mengelola sampah dengan benar melalui video-video edukatif yang menarik dan mudah dipahami.
          Konten disajikan secara visual untuk membantu meningkatkan kesadaran dan tindakan nyata dalam menjaga lingkungan.
        </p>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>

<script>
  let player;

  function onYouTubeIframeAPIReady() {
    player = new YT.Player('video-player', {
      videoId: 'tVuNnac7m0o',
      events: {
        onReady: onPlayerReady
      },
      playerVars: {
        'autoplay': 0,
        'controls': 1,
        'mute': 1 // autoplay won't work unless muted
      }
    });
  }

  function onPlayerReady(event) {
    const section = document.querySelector('#edukasi');

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          player.playVideo();
        } else {
          player.pauseVideo();
        }
      });
    }, { threshold: 0.5 });

    observer.observe(section);
  }

  AOS.init(); // initialize animation after video is handled
</script>
@endpush
