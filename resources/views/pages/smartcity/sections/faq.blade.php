@push('styles')
<style>
  .accordion-button::after {
    content: '▾';
    transition: transform 0.3s ease;
    font-size: 1.25rem;
    margin-left: 0.5rem;
  }
  .accordion-button[aria-expanded="true"]::after {
    transform: rotate(180deg);
  }
  .accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease;
    box-sizing: border-box;
  }
  .accordion-content.open {
    max-height: 500px; 
  }


  @media (min-width: 320px) { .faq-pad { padding: 48px 34px; } }
  @media (min-width: 768px) { .faq-pad { padding: 48px 64px; } }
</style>
@endpush

<section id="faq" class="bg-blue-400 py-10 pb-12 px-4">
  <div class="max-w-6xl mx-auto">
    <h2
      class="text-3xl md:4xl font-bold text-white tracking-wide mb-8 border-b-2 border-white w-fit mx-auto text-center z-[1]"
      data-aos="fade-down"
    >
      FAQ's
    </h2>

    <div
      class="faq-pad bg-white rounded-xl mb-8 shadow-lg w-full"
      data-aos="fade-up"
    >
      <div class="text-center mb-8">
        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 165 165" fill="none" class="mx-auto mb-4">
          <path d="M49.1667 49.1435H115.833M49.1667 82.4584H99.1667M103.333 128.266L82.5 149.088L57.5 124.102H32.5C25.8696 124.102 19.5107 121.469 14.8223 116.784C10.1339 112.098 7.5 105.742 7.5 99.1158V32.4861C7.5 25.8594 10.1339 19.5041 14.8223 14.8183C19.5107 10.1325 25.8696 7.5 32.5 7.5H132.5C139.13 7.5 145.489 10.1325 150.178 14.8183C154.866 19.5041 157.5 25.8594 157.5 32.4861V69.9653M140.833 157.417V157.5M140.833 132.431C144.569 132.419 148.193 131.155 151.125 128.841C154.057 126.527 156.127 123.296 157.005 119.667C157.882 116.038 157.516 112.219 155.965 108.822C154.414 105.426 151.768 102.647 148.45 100.931C145.135 99.2341 141.343 98.7079 137.69 99.4383C134.038 100.169 130.74 102.113 128.333 104.954" stroke="#155DFC" stroke-width="15" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <button class="bg-blue-600 text-white text-sm md:text-base font-semibold py-2 px-6 rounded-xl shadow-md transition w-auto">
          Tanya Jawab Seputar SmartCity
        </button>
      </div>

      @php
        $faqs = [
          [
            'q' => 'Apa itu SmartCity.id?',
            'a' => 'SmartCity.id adalah platform informasi dan edukasi mengenai konsep Smart City atau kota cerdas. Website ini berfungsi untuk memperkenalkan bagaimana teknologi dapat dimanfaatkan untuk meningkatkan kualitas hidup masyarakat, efisiensi layanan publik, serta pembangunan berkelanjutan di daerah.',
          ],
          [
            'q' => 'Apa tujuan utama website SmartCity.id?',
            'a' => 'Tujuan utama website ini adalah menjadi pusat informasi dan komunikasi mengenai inisiatif Smart City, termasuk enam pilarnya: Smart Governance, Smart Branding, Smart Economy, Smart Environment, Smart Living, dan Smart Society.
Melalui platform ini, masyarakat diharapkan lebih memahami dan berpartisipasi dalam transformasi digital kota.',
          ],
          [
            'q' => 'Apa yang dimaksud dengan enam pilar Smart City?',
            'a' => 'Enam pilar Smart City mencakup aspek utama dalam membangun kota cerdas, yaitu:
                    <ul class="list-decimal list-inside pl-2 space-y-1 mt-2">
                        <li>Smart Governance, pemerintahan berbasis teknologi dan transparansi.</li>
                        <li>Smart Branding, membangun citra dan identitas kota</li>
                        <li>Smart Economy, mendorong ekonomi digital dan inovasi bisnis.</li>
                        <li>Smart Environment, pengelolaan lingkungan yang berkelanjutan.</li>
                        <li>Smart Living, meningkatkan kualitas hidup warga.</li>
                        <li>Smart Society, memberdayakan masyarakat agar adaptif dan melek teknologi.</li>
                    </ul>',
          ],
          [
            'q' => 'Apa saja fitur yang tersedia di SmartCity.id?',
            'a' => 'Fitur utama meliputi:
                    <ul class="list-decimal list-inside pl-2 space-y-1 mt-2">
                        <li>Informasi enam pilar Smart City lengkap dengan contoh implementasinya.</li>
                        <li>Berita dan artikel terbaru seputar inovasi teknologi perkotaan.</li>
                        <li>Data dan peta interaktif, untuk melihat perkembangan infrastruktur digital.</li>
                        <li>Form aspirasi dan pengaduan masyarakat, agar warga bisa ikut terlibat dalam pembangunan.</li>
                    </ul>',
          ],
          [
            'q' => 'Bagaimana saya bisa ikut mendukung pengembangan Smart City di daerah saya?',
            'a' => 'Kamu dapat mulai dengan:
                    <ul class="list-decimal list-inside pl-2 space-y-1 mt-2">
                        <li>Aktif menggunakan layanan digital pemerintah.</li>
                        <li>Menyebarkan informasi positif tentang inovasi kota.</li>
                        <li>Bergabung dengan komunitas atau kegiatan sosial yang berorientasi pada teknologi dan keberlanjutan.</li>
                    </ul>',
          ],
        ];
      @endphp

      <div class="space-y-6">
        @foreach($faqs as $faq)
        <div class="border rounded-xl shadow-md overflow-hidden">
          <button
            class="w-full text-left px-6 py-4 font-semibold text-gray-800 text-sm md:text-base sm:text-sm flex justify-between items-center accordion-button"
            aria-expanded="false"
            onclick="toggleAccordion(this)"
          >
            {{ $faq['q'] }}
          </button>
          <div class="accordion-content border-t border-gray-200 text-gray-600 text-base md:text-sm sm:text-xs leading-relaxed">
            <div class="px-6 py-4">
              {!! $faq['a'] !!}
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>

  function toggleAccordion(button) {
    const content = button.nextElementSibling;
    const isOpen = button.getAttribute('aria-expanded') === 'true';
    document.querySelectorAll('.accordion-button').forEach(btn => btn.setAttribute('aria-expanded', 'false'));
    document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('open'));
    if (!isOpen) {
      button.setAttribute('aria-expanded', 'true');
      content.classList.add('open');
    }
  }
</script>
@endpush