@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
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
    max-height: 500px; /* cukup besar untuk semua jawaban singkat */
  }

      /* Responsif untuk ikon game */
      @media (min-width: 320px) {
      .faq-pad {
        padding: 48px 34px;"
      }
    }

    @media (min-width: 375px) {
      .faq-pad {
        padding: 48px 38px;"
      }
    }

    @media (min-width: 425px) {
      .faq-pad {
        padding: 48px 38px;"
      }
    }

    @media (min-width: 768px) {
      .faq-pad {
        padding: 48px 64px;"
      }
    }

    @media (min-width: 1024px) {
      .faq-pad, {
        padding: 48px 64px;"
      }
    }

    @media (min-width: 1440px) {
      .faq-pad {
        padding: 48px 64px;"
      }
    }

    /* Responsif untuk ikon game */
    @media (min-width: 320px) {
      .text-button {
        background-color: #5E936C;
        width: 198px;
      }
    }

    @media (min-width: 375px) {
      .text-button {
        background-color: #5E936C;
        width: 198px;
      }
    }

    @media (min-width: 425px) {
      .text-button {
        background-color: #5E936C;
        width: 198px;
      }
    }

    @media (min-width: 768px) {
      .text-button {
        background-color: #5E936C;
        width: 55%;
      }
    }

    @media (min-width: 1024px) {
      .text-button {
        background-color: #5E936C;
        width: 36%;
      }
    }

    @media (min-width: 1440px) {
      .text-button {
        background-color: #5E936C;
        width: 36%;
      }
    }


</style>
@endpush

<section id="faq" class="bg-green-400 py-10 pb-12 px-4">
  <div class="max-w-6xl mx-auto">
    <h2
      class="text-4xl font-bold text-white tracking-wide mb-8 border-b-2 border-white w-fit mx-auto text-center z-[1]"
      data-aos="fade-down"
    >
      FAQ's
    </h2>

    <div
      class="faq-pad bg-white rounded-xl mb-8 shadow-lg w-full"
      data-aos="fade-up"
    >
      <div class="text-center mb-8">
        <img
          src="{{ asset('img/faq.png') }}"
          alt="FAQ Icon"
          class="mx-auto mb-4"
          style="width: 128px; height: 128px;"
        >
        <button class="text-button text-white font-semibold py-2 px-6 rounded-xl shadow-md transition">
          Tanya Jawab Seputar CerdasSampah
        </button>
      </div>

      @php
        $faqs = [
          [
            'q' => 'Apa itu CerdasSampah ?',
            'a' => 'CerdasSampah adalah platform edukasi dan layanan berbasis teknologi yang membantu masyarakat dalam memilah, melaporkan, dan memahami pengelolaan sampah secara cerdas dan ramah lingkungan.',
          ],
          [
            'q' => 'Bagaimana cara kerja fitur Pindai Sampah di CerdasSampah ?',
            'a' => 'Fitur ini menggunakan teknologi Machine Learning untuk mengenali jenis sampah dari foto yang diunggah pengguna, lalu memberikan informasi klasifikasi serta tips pengelolaannya.',
          ],
          [
            'q' => 'Bisakah saya melaporkan TPS liar melalui CerdasSampah ?',
            'a' => 'Bisa! Kamu cukup mengunggah foto dan lokasi TPS liar lewat fitur pelaporan. Tim kami akan meneruskan laporan tersebut ke dinas kebersihan terkait.',
          ],
          [
            'q' => 'Apakah game Pilah Sampah di CerdasSampah hanya untuk anak-anak ?',
            'a' => 'Tidak. Game ini dibuat untuk semua usia sebagai media belajar yang seru. Melalui game ini, kamu bisa berlatih memilih sampah organik, anorganik, dan B3 dengan cara menyenangkan.',
          ],
          [
            'q' => 'Bagaimana saya tahu kapan truk sampah akan datang ke TPS terdekat ?',
            'a' => 'Kamu bisa melihat jadwal rute pengangkutan sampah langsung di dalam aplikasi. Informasi ini membantu kamu menyesuaikan waktu membuang sampah agar tidak terjadi penumpukan dan lingkungan tetap tertib dan bersih.',
          ],
        ];
      @endphp

      <div class="space-y-6">
        @foreach($faqs as $faq)
        <div class="border rounded-xl shadow-md overflow-hidden">
          <button
            class="w-full text-left px-6 py-4 font-semibold text-gray-800 text-lg md:text-base sm:text-sm flex justify-between items-center accordion-button"
            aria-expanded="false"
            onclick="toggleAccordion(this)"
          >
            {{ $faq['q'] }}
          </button>
          <div class="accordion-content border-t border-gray-200 text-gray-600 text-base md:text-sm sm:text-xs leading-relaxed">
            <div class="px-6 py-4">
              {{ $faq['a'] }}
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();

  function toggleAccordion(button) {
    const content = button.nextElementSibling;
    const isOpen = button.getAttribute('aria-expanded') === 'true';

    // Tutup semua yang terbuka
    document.querySelectorAll('.accordion-button').forEach(btn => btn.setAttribute('aria-expanded', 'false'));
    document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('open'));

    // Buka elemen yang diklik jika belum terbuka
    if (!isOpen) {
      button.setAttribute('aria-expanded', 'true');
      content.classList.add('open');
    }
  }
</script>
@endpush
