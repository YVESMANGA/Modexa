<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"
/>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <!-- CSS et scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
  .swiper-button-prev,
  .swiper-button-next {
    color: #8B4513; /* Marron */
  }

  /* Facultatif : changer la couleur au hover */
  .swiper-button-prev:hover,
  .swiper-button-next:hover {
    color: #5e3010; /* Marron plus foncé */
  }

  /* Couleur des bullets */
  .swiper-pagination-bullet {
    background: #8B4513; /* Marron */
    opacity: 0.4;
  }

  /* Bullet actif */
  .swiper-pagination-bullet-active {
    background: #8B4513; /* Marron actif */
    opacity: 1;
  }

  @keyframes bounceIn {
  0% {
    transform: scale(0.8);
    opacity: 0;
  }
  60% {
    transform: scale(1.05);
    opacity: 1;
  }
  100% {
    transform: scale(1);
  }
}
.animate-bounceIn {
  animation: bounceIn 0.4s ease-out;
}

</style>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-[#f5f5dc]">
        <!-- Navigation -->
        

        <!-- Header -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('header')
            </div>
        </header>
        @endif

        <!-- Contenu principal -->
        <main>
        @yield('content')
        @if(session('success_commande'))
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        style="backdrop-filter: blur(3px);"
    >
        <div class="bg-white p-6 rounded-2xl shadow-xl text-center animate-bounceIn" style="min-width: 300px;">
            <div class="flex justify-center mb-4">
            <img src="{{ asset('storage/home/l.jpg') }}" alt="ModeMarket Logo" width="60" height="60" class="object-contain" />
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Commande validée</h2>
            <p class="text-gray-600">🎉 Merci pour votre achat !. L'equipe Modexa vous remercie!</p>
        </div>
    </div>
@endif


        </main>
    </div>
</body>
</html>
