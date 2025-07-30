@extends('components.admin-layout')

@section('content')
@include('layouts.navbar')

<div class="max-w-7xl mx-auto p-6">
    {{-- Nom de la catégorie dans une carte blanche --}}
    <div class="bg-white rounded-lg shadow p-6 mb-8 text-center">
        <h1 class="text-4xl font-bold text-[#8B4513]">{{ $categorie->nom }}</h1>
    </div>

    {{-- Filtre par boutique --}}
    <div class="mb-6">
        <label for="boutique" class="block text-gray-700 font-semibold mb-2">Filtrer par boutique :</label>
        <select id="boutique" class="w-full sm:w-64 border rounded p-2" onchange="filterByBoutique()">
            <option value="all">Toutes les boutiques</option>
            @foreach($categorie->produits->groupBy('boutique.nom') as $nomBoutique => $produits)
                <option value="{{ $nomBoutique }}">{{ $nomBoutique }}</option>
            @endforeach
        </select>
    </div>

    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Produits disponibles</h2>

    @if($categorie->produits->count() > 0)
        @foreach($categorie->produits->groupBy('boutique.nom') as $nomBoutique => $produits)
            <div class="mb-6 boutique-section" data-boutique="{{ $nomBoutique }}">
                <h3 class="text-2xl font-extrabold text-[#8B4513] bg-[#fff7ed] px-4 py-3 rounded shadow mb-4 text-center">{{ $nomBoutique }}</h3>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($produits as $produit)
                        @php
                          $prodJson = json_encode([
                            'id' => $produit->id,
                            'nom' => $produit->nom,
                            'prix' => $produit->prix,
                            'promo_prix' => $produit->promo_prix,
                            'image' => asset('storage/' . $produit->image),
                            'description' => $produit->description ?? '',
                          ]);
                        @endphp
                        <div 
                          onclick='showModal({!! $prodJson !!})'
                          class="border rounded-lg p-3 shadow hover:shadow-md transition text-sm cursor-pointer"
                        >
                            @if($produit->image)
                                <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-full h-40 object-cover rounded mb-3">
                            @endif

                            <h4 class="text-lg font-bold">{{ $produit->nom }}</h4>
                            <p class="font-semibold">{{ number_format($produit->prix, 2, ',', ' ') }} Frcfa</p>
                            @if($produit->promo_prix)
                                <p class="text-red-600 font-semibold">Promo : {{ number_format($produit->promo_prix, 2, ',', ' ') }} Frcfa</p>
                            @endif

                            {{-- Contrôle quantité + bouton ajouter --}}
                            <div class="mt-3 flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0 sm:space-x-2" onclick="event.stopPropagation()">
                                <div class="flex items-center space-x-2">
                                    <button onclick="decreaseQty({{ $produit->id }})" class="px-2 py-1 bg-gray-200 rounded text-lg font-bold sm:text-base">−</button>
                                    <input type="number" id="qty-{{ $produit->id }}" value="1" min="1" class="w-12 text-center border rounded sm:w-14">
                                    <button onclick="increaseQty({{ $produit->id }})" class="px-2 py-1 bg-gray-200 rounded text-lg font-bold sm:text-base">+</button>
                                </div>
                                <button 
                                  onclick="addToCart({{ $produit->id }}, '{{ addslashes($produit->nom) }}', {{ $produit->promo_prix ?? $produit->prix }}, '{{ asset('storage/' . $produit->image) }}')"
                                  class="bg-[#8B4513] text-white px-3 py-2 rounded hover:bg-[#ecb187] transition w-full sm:w-auto"
                                >
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <p class="text-gray-500">Aucun produit dans cette catégorie.</p>
    @endif
</div>

{{-- Modal Produit --}}
<div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
  <div class="bg-white rounded-lg p-6 w-full max-w-lg relative">
    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-3xl font-bold leading-none">&times;</button>

    <img id="modal-image" src="" alt="Produit" class="w-full h-64 object-cover rounded mb-4">
    <h2 id="modal-title" class="text-2xl font-bold mb-2"></h2>
    <p id="modal-price" class="text-lg font-semibold mb-2 text-gray-800"></p>
    <p id="modal-description" class="text-gray-600 mb-4"></p>

    <div class="flex items-center space-x-2 mb-4">
      <button onclick="decreaseQtyInModal()" class="px-3 py-1 bg-gray-200 rounded text-xl font-bold">−</button>
      <input type="number" id="modal-qty" value="1" min="1" class="w-16 text-center border rounded">
      <button onclick="increaseQtyInModal()" class="px-3 py-1 bg-gray-200 rounded text-xl font-bold">+</button>
    </div>

    <button id="modal-add-btn" class="bg-[#8B4513] text-white px-4 py-2 rounded hover:bg-[#ecb187] w-full">
      Ajouter au panier
    </button>
  </div>
</div>
@endsection

@section('scripts')
<script>
  let cart = [];
  let modalProduct = null;

  // Quantité dans liste produits
  function increaseQty(id) {
    const input = document.getElementById(`qty-${id}`);
    input.value = parseInt(input.value) + 1;
  }
  function decreaseQty(id) {
    const input = document.getElementById(`qty-${id}`);
    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
  }

  // Quantité dans modal
  function increaseQtyInModal() {
    const input = document.getElementById('modal-qty');
    input.value = parseInt(input.value) + 1;
  }
  function decreaseQtyInModal() {
    const input = document.getElementById('modal-qty');
    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
  }

  // Affiche le modal produit
  function showModal(product) {
    modalProduct = product;

    document.getElementById('modal-image').src = product.image;
    document.getElementById('modal-title').textContent = product.nom;
    document.getElementById('modal-price').textContent = (product.promo_prix ?? product.prix) + ' Frcfa';
    document.getElementById('modal-description').textContent = product.description || 'Pas de description disponible.';
    document.getElementById('modal-qty').value = 1;

    document.getElementById('modal-add-btn').onclick = () => {
      const qty = parseInt(document.getElementById('modal-qty').value);
      addToCart(product.id, product.nom, product.promo_prix ?? product.prix, product.image, qty);
      closeModal();
    };

    document.getElementById('product-modal').classList.remove('hidden');
  }

  // Ferme le modal
  function closeModal() {
    document.getElementById('product-modal').classList.add('hidden');
  }

  // Ajoute au panier (fonction modifiée pour qté optionnelle)
  function addToCart(id, name, price, image = null, qty = null) {
    if (qty === null) {
      const input = document.getElementById(`qty-${id}`);
      qty = input ? parseInt(input.value) : 1;
    }
    const existing = cart.find(item => item.id === id);
    if (existing) {
      existing.qty += qty;
    } else {
      cart.push({ id, name, price, qty, image });
    }
    updateCartUI();
  }

  // Met à jour l'affichage du panier (exemple simple)
  function updateCartUI() {
    const cartSidebar = document.querySelector('#cart-sidebar .flex-grow');
    const badge = document.querySelector('#show-cart span');
    let totalItems = 0;

    if (!cartSidebar || !badge) return;

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
          <span class="font-semibold">${(item.price * item.qty).toFixed(2)} Frcfa</span>
        </li>
      `;
    });
    html += '</ul>';
    cartSidebar.innerHTML = html;
    badge.textContent = totalItems;
  }

  // Filtre produits par boutique
  function filterByBoutique() {
    const selected = document.getElementById('boutique').value;
    const sections = document.querySelectorAll('.boutique-section');
    sections.forEach(section => {
      const boutique = section.getAttribute('data-boutique');
      section.style.display = (selected === 'all' || selected === boutique) ? 'block' : 'none';
    });
  }
</script>
@endsection
