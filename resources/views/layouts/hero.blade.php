<section class="relative shadow-md w-full mt-0 mb-0 flex items-center justify-center overflow-hidden h-[400px] sm:h-[500px] md:h-[600px]">

  <div class="absolute inset-0">
    <img src="{{ asset('storage/home/h1.png') }}" alt="Slide 1" class="slide active" />
    <img src="{{ asset('storage/home/h2.png') }}" alt="Slide 2" class="slide" />
    <img src="{{ asset('storage/home/h3.png') }}" alt="Slide 3" class="slide" />
  </div>

  <div class="relative z-10 flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8 h-full">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-3 sm:mb-4 text-white drop-shadow-lg">
      Découvrez la meilleure marketplace de mode
    </h1>
    <p class="text-base sm:text-lg mb-4 sm:mb-6 text-white drop-shadow">
      Commandez facilement dans vos boutiques préférées, tout au même endroit.
    </p>
    <a href="{{ route('boutiques.all') }}"
       class="inline-block bg-[#8B4513] text-white px-4 py-2 sm:px-6 sm:py-3 rounded-full font-medium hover:bg-[#5e3010] transition text-sm sm:text-base"
    >
      Voir les boutiques
    </a>
  </div>
</section>

<style>
  .slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    pointer-events: none;
  }

  .slide.active {
    opacity: 1;
    pointer-events: auto;
  }
</style>

<script>
  const slides = document.querySelectorAll('.slide');
  let current = 0;

  setInterval(() => {
    slides[current].classList.remove('active');
    current = (current + 1) % slides.length;
    slides[current].classList.add('active');
  }, 4000);
</script>
