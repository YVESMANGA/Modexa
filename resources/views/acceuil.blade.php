@extends('components.admin-layout')

@section('content')
@include('layouts.navbar')
@include('layouts.hero')
@include('layouts.category')


{{-- Section Boutiques --}}
<section class="max-w-7xl mx-auto px-4 py-6">
  <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Nos Boutiques</h2>

  <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">


    @foreach($boutiques as $boutique)
      <a href="{{ route('boutiques.details', $boutique->id) }}">
        <div class="flex flex-col items-center rounded-lg overflow-hidden shadow hover:shadow-md transition">
          {{-- Image --}}
          <div class="w-full h-60 overflow-hidden group">
            <img
              src="{{ asset('storage/' . $boutique->logo) }}"
              alt="{{ $boutique->nom }}"
              class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
            />
          </div>

          {{-- Nom --}}
          <div class="p-4 w-full text-center">
            <h3 class="text-lg font-semibold text-[#8B4513]">
              {{ $boutique->nom }}
            </h3>
          </div>
        </div>
      </a>
    @endforeach
  </div>

  {{-- Bouton "voir plus" --}}
  <div class="text-center mt-8">
    <a href="{{ route('boutiques.all') }}" class="inline-block bg-[#8B4513] text-white px-6 py-3 rounded-full font-medium hover:bg-[#5e3010] transition">Voir plus de boutiques</a>
  </div>
</section>


@if (session('success'))
  <div id="success-popup" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white max-w-md w-full p-6 rounded-lg shadow-lg text-center relative animate-fade-in">
      <button onclick="document.getElementById('success-popup').remove()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
      <div class="flex justify-center mb-4">
        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h2 class="text-xl font-semibold text-green-700 mb-2">Commande validée !</h2>
      <p class="text-gray-700">{{ session('success') }}</p>
    </div>
  </div>

  {{-- Supprimer panier du localStorage --}}
  <script>
    localStorage.removeItem('cart');
  </script>

  {{-- Animation fade-in --}}
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
      animation: fade-in 0.4s ease-out;
    }
  </style>
@endif
@include('layouts.discover')
@include('layouts.footer')
@endsection
