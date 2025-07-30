@extends('components.admin-layout')

@section('content')
@include('layouts.navbar')

<div class="max-w-7xl mx-auto p-6">
    {{-- Nom de la boutique centré dans une carte blanche --}}
    <div class="bg-white rounded-lg shadow p-6 mb-8 text-center">
        <h1 class="text-4xl font-bold text-[#8B4513]">{{ $boutique->nom }}</h1>
        @if($boutique->description)
            <p class="mt-2 text-gray-700">{{ $boutique->description }}</p>
        @endif
    </div>

    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Produits disponibles</h2>

    @if($boutique->produits->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($boutique->produits as $produit)
            <div class="border rounded-lg p-3 shadow hover:shadow-md transition text-sm">
                @if($produit->image)
                    <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-40 object-cover rounded mb-3">
                @endif

                <h3 class="text-lg font-bold">{{ $produit->nom }}</h3>
                <p class="font-semibold">{{ number_format($produit->prix, 2, ',', ' ') }} Frcfa</p>
                @if($produit->promo_prix)
                    <p class="text-red-600 font-semibold">Promo : {{ number_format($produit->promo_prix, 2, ',', ' ') }} Frcfa</p>
                @endif

                {{-- Contrôle quantité + bouton ajouter --}}
                <div class="mt-3 flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0 sm:space-x-2">
                    <div class="flex items-center space-x-2">
                        <button onclick="decreaseQty({{ $produit->id }})" class="px-2 py-1 bg-gray-200 rounded text-lg font-bold sm:text-base">−</button>
                        <input type="number" id="qty-{{ $produit->id }}" value="1" min="1" class="w-12 text-center border rounded sm:w-14">
                        <button onclick="increaseQty({{ $produit->id }})" class="px-2 py-1 bg-gray-200 rounded text-lg font-bold sm:text-base">+</button>
                    </div>
                    <button onclick="addToCart({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->promo_prix ?? $produit->prix }}, '{{ asset('storage/' . $produit->image) }}')"
                        class="bg-[#8B4513] text-white px-3 py-2 rounded hover:bg-[#ecb187] transition w-full sm:w-auto">
                         Ajouter
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Aucun produit disponible pour cette boutique.</p>
    @endif
</div>
@endsection

{{-- Script quantité + panier --}}
<script>
  let cart = [];

  function increaseQty(id) {
    const input = document.getElementById(`qty-${id}`);
    input.value = parseInt(input.value) + 1;
  }

  function decreaseQty(id) {
    const input = document.getElementById(`qty-${id}`);
    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
  }

  function addToCart(id, name, price, image = null) {
    const qty = parseInt(document.getElementById(`qty-${id}`).value);
    const existing = cart.find(item => item.id === id);
    if (existing) {
      existing.qty += qty;
    } else {
      cart.push({ id, name, price, qty, image });
    }
    updateCartUI();
  }

  function updateCartUI() {
    const cartSidebar = document.querySelector('#cart-sidebar .flex-grow');
    const badge = document.querySelector('#show-cart span');
    let totalItems = 0;

    if (cart.length === 0) {
      cartSidebar.innerHTML = `<p class="text-gray-500">Aucun produit actuellement sélectionné.</p>`;
      badge.textContent = 0;
      return;
    }

    let html = '<ul class="space-y-3">';
    cart.forEach(item => {
      totalItems += item.qty;
      html += `
        <li class="flex justify-between items-center">
          <span>${item.name} x ${item.qty}</span>
          <span class="font-semibold">${(item.price * item.qty).toFixed(2)} €</span>
        </li>
      `;
    });
    html += '</ul>';
    cartSidebar.innerHTML = html;

    badge.textContent = totalItems;
  }
</script>
