@extends('components.admin-layout')

@section('content')
@include('layouts.navbar')

<section class="max-w-7xl mx-auto px-4 py-10">
  <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Toutes nos Boutiques</h2>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($boutiques as $boutique)
      <a href="{{ route('boutiques.details', $boutique->id) }}">
        <div class="flex flex-col items-center rounded-lg overflow-hidden shadow hover:shadow-md transition">
          <div class="w-full h-60 overflow-hidden group">
            <img
              src="{{ asset('storage/' . $boutique->logo) }}"
              alt="{{ $boutique->nom }}"
              class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
            />
          </div>
          <div class="p-4 w-full text-center">
            <h3 class="text-lg font-semibold text-gray-800">{{ $boutique->nom }}</h3>
          </div>
        </div>
      </a>
    @endforeach
  </div>

  <div class="mt-10">
    {{ $boutiques->links() }}
  </div>
</section>

@include('layouts.footer')
@endsection
