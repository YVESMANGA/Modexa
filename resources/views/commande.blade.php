@extends('components.admin-layout')

@section('content')
@include('layouts.navbar')

<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-lg">

  <h1 class="text-3xl font-bold mb-6 text-[#8B4513]">Votre commande</h1>

  {{-- Liste produits commandés --}}
  <div id="order-items" class="mb-6">
    <p>Chargement des produits...</p>
  </div>

  <h2 class="text-2xl font-semibold text-gray-700 mb-4 border-b pb-2">Coordonnées</h2>

  {{-- Formulaire --}}
  <form id="order-form" method="POST" action="{{ route('order.store') }}" class="space-y-4" onsubmit="return prepareOrder(event)">
    @csrf

    <input type="hidden" name="order_data" id="order_data">

    <div>
      <label for="nom" class="block font-semibold text-gray-700 mb-1">Nom :</label>
      <input type="text" id="nom" name="nom" required class="border border-gray-300 p-2 rounded w-full" />
    </div>

    <div>
      <label for="prenom" class="block font-semibold text-gray-700 mb-1">Prénom :</label>
      <input type="text" id="prenom" name="prenom" required class="border border-gray-300 p-2 rounded w-full" />
    </div>

    <div>
      <label for="telephone" class="block font-semibold text-gray-700 mb-1">Téléphone :</label>
      <input type="tel" id="telephone" name="telephone" required class="border border-gray-300 p-2 rounded w-full" />
    </div>

    <div>
      <label for="adresse" class="block font-semibold text-gray-700 mb-1">Adresse :</label>
      <textarea id="adresse" name="adresse" class="border border-gray-300 p-2 rounded w-full"></textarea>
    </div>

    {{-- Mode de paiement --}}
    <div>
      <label for="mode_paiement" class="block font-semibold text-gray-700 mb-1">Mode de paiement :</label>
      <select id="mode_paiement" name="mode_paiement" required class="border border-gray-300 p-2 rounded w-full">
        <option value="">-- Sélectionnez --</option>
        <option value="en_ligne">Paiement en ligne</option>
        <option value="a_la_livraison">Paiement à la livraison</option>
      </select>
    </div>

    {{-- Mode de livraison --}}
    <div>
      <label for="mode_livraison" class="block font-semibold text-gray-700 mb-1">Mode de livraison :</label>
      <select id="mode_livraison" name="mode_livraison" required class="border border-gray-300 p-2 rounded w-full">
        <option value="">-- Sélectionnez --</option>
        <option value="a_domicile">Livraison à domicile</option>
        <option value="retrait_boutique">Retrait en boutique</option>
      </select>
    </div>

    {{-- Boutons --}}
    <div class="flex justify-between gap-4">
      <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-green-700 transition w-full">
        Valider la commande
      </button>
      <button type="button" onclick="cancelOrder()" class="bg-red-500 text-white font-semibold py-2 px-4 rounded hover:bg-red-600 transition w-full">
        Annuler
      </button>
    </div>

  </form>
</div>

<script>
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const orderItemsDiv = document.getElementById('order-items');

  function displayOrderItems() {
    if (cart.length === 0) {
      orderItemsDiv.innerHTML = '<p class="text-gray-500">Votre panier est vide.</p>';
      return;
    }

    let total = 0;
    let html = '<ul class="divide-y divide-gray-200 mb-4">';
    cart.forEach(item => {
      const itemTotal = item.qty * item.price;
      total += itemTotal;

      html += `
        <li class="flex items-center py-4">
          <img src="${item.image ?? '/images/default.jpg'}" alt="${item.name}" class="w-16 h-16 object-cover rounded mr-4" />
          <div class="flex-grow">
            <p class="font-semibold">${item.name}</p>
            <p class="text-sm text-gray-600">x${item.qty} - ${(itemTotal).toFixed(2)} €</p>
          </div>
        </li>
      `;
    });
    html += '</ul>';
    html += `<p class="text-right text-lg font-semibold text-gray-800">Total : ${total.toFixed(2)} €</p>`;

    orderItemsDiv.innerHTML = html;
  }

  function prepareOrder(e) {
    if (cart.length === 0) {
      alert("Votre panier est vide.");
      e.preventDefault();
      return false;
    }
    document.getElementById('order_data').value = JSON.stringify(cart);
    return true;
  }

  function cancelOrder() {
    if (confirm("Voulez-vous vraiment annuler cette commande ?")) {
      localStorage.removeItem('cart');
      window.location.href = "{{ url('/') }}";
    }
  }

  displayOrderItems();
</script>

@endsection
