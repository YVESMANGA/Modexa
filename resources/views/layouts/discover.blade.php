<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<section class="bg-[#fcfbfa] py-16 px-4">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-10">

    {{-- Texte --}}
    <div class="w-full md:w-1/2 text-center md:text-left">
      <h2 class="text-3xl md:text-4xl font-bold text-[#5e3010] mb-4">
        Voyagez au cœur des tendances
      </h2>
      <p class="text-lg text-gray-700 mb-6">
        Accédez à toutes vos boutiques préférées avec un seul billet.
        Une expérience shopping simplifiée, stylée et sans frontières.
      </p>
    </div>

    {{-- Carrousel Swiper --}}
    <div class="w-full md:w-1/2">
      <div class="swiper mySwiper rounded-lg shadow-md h-64 sm:h-72 md:h-80">
        <div class="swiper-wrapper h-full">
          <div class="swiper-slide h-full">
            <img src="{{ asset('storage/home/end.jpg') }}" alt="Shopping 1" class="w-full h-full object-cover rounded-lg" />
          </div>
          <div class="swiper-slide h-full">
            <img src="{{ asset('storage/home/end2.jpg') }}" alt="Shopping 2" class="w-full h-full object-cover rounded-lg" />
          </div>
          <div class="swiper-slide h-full">
            <img src="{{ asset('storage/home/end3.jpg') }}" alt="Shopping 3" class="w-full h-full object-cover rounded-lg" />
          </div>
          <div class="swiper-slide h-full">
            <img src="{{ asset('storage/home/end5.jpg') }}" alt="Shopping 4" class="w-full h-full object-cover rounded-lg" />
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Script d’activation -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.mySwiper', {
      loop: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      effect: 'slide',
    });
  });
</script>
