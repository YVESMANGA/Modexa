<footer class="bg-[#f5f5dc] border-t border-gray-300 py-10 mt-16">
  <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between gap-8">
    {{-- Logo / Nom --}}
    <div>
      <a href="{{ url('/') }}" class="text-2xl font-bold text-[#8B4513]">
        Modexa
      </a>
      <p class="text-sm text-gray-600 mt-2">
        Votre portail unique vers la mode.
      </p>
    </div>

    {{-- Liens rapides --}}
    <div class="flex flex-col gap-2">
      <a href="{{ url('/') }}" class="text-gray-700 hover:text-[#8B4513]">Accueil</a>
      <a href="{{ url('/boutiques') }}" class="text-gray-700 hover:text-[#8B4513]">Boutiques</a>
      <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-[#8B4513]">Contact</a>
    </div>

    {{-- Réseaux sociaux --}}
    <div class="flex gap-4 items-center">
      <a href="#" class="text-gray-700 hover:text-[#8B4513]">
        <i class="fab fa-instagram text-[20px]"></i>
      </a>
      <a href="#" class="text-gray-700 hover:text-[#8B4513]">
        <i class="fab fa-facebook-f text-[20px]"></i>
      </a>
      <a href="#" class="text-gray-700 hover:text-[#8B4513]">
        <i class="fab fa-twitter text-[20px]"></i>
      </a>
    </div>
  </div>

  {{-- Bas du footer --}}
  <div class="mt-8 text-center text-sm text-gray-500">
    &copy; {{ date('Y') }} ModeMarket. Tous droits réservés.
  </div>
</footer>
