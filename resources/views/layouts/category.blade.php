<section class="py-16 px-4 bg-white">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-center text-[#5e3010] mb-10">
      Nos catégories
    </h2>

    <div class="swiper categoriesSwiper">
      <div class="swiper-wrapper">
        @foreach($categories as $category)
          <a href="{{ route('category.show', $category->id) }}"
             class="swiper-slide bg-white w-[220px] h-[300px] flex flex-col justify-between rounded-lg overflow-hidden shadow hover:shadow-lg transition duration-300 p-2">

            {{-- Image à hauteur fixe --}}
            <div class="w-full h-[180px] flex items-center justify-center overflow-hidden">
              <img src="{{ asset('storage/' . $category->image) }}"
                   alt="{{ $category->nom }}"
                   class="h-full w-auto object-contain" />
            </div>

            {{-- Nom de la catégorie --}}
            <div class="bg-[#fdf7f1] w-full text-center p-2 rounded h-[60px] flex items-center justify-center">
              <h3 class="text-md font-semibold text-[#8B4513] truncate">
                {{ $category->nom }}
              </h3>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.categoriesSwiper', {
      slidesPerView: 3, // Affiche 3 par défaut
      spaceBetween: 12,
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      breakpoints: {
        640: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 40,
        },
      }
    });
  });
</script>
