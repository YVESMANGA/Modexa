<nav class="bg-white shadow-md sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center space-x-2">
      <img src="{{ asset('storage/home/l.jpg') }}" alt="ModeMarket Logo" width="60" height="60" class="object-contain" />
      <span class="text-xl font-bold text-gray-800 hidden sm:inline">Modexa</span>
    </a>

    {{-- Liens + boutons --}}
    <div class="flex items-center space-x-4">
      <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-500 transition">Accueil</a>
      <a href="{{ url('/boutiques') }}" class="text-gray-700 hover:text-blue-500 transition">Boutiques</a>

      {{-- Bouton Catégories --}}
      <button id="show-categories" aria-label="Afficher les catégories"
        class="text-gray-700 hover:text-blue-500 transition focus:outline-none">
        Catégories
      </button>

      {{-- Auth Links --}}
      @guest
        <a href="{{ route('admin') }}"
          class="text-gray-700 hover:text-blue-500 transition font-semibold px-3 py-1 border border-blue-500 rounded-md">
          Se connecter
        </a>
        <a href="{{ route('register') }}"
          class="text-white bg-blue-600 hover:bg-blue-700 transition font-semibold px-3 py-1 rounded-md">
          S'inscrire
        </a>
      @else
        <a href="{{ route('dashboard') }}"
          class="text-gray-700 hover:text-blue-500 transition font-semibold px-3 py-1 rounded-md">
          {{ Auth::user()->name }}
        </a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit"
            class="text-gray-700 hover:text-blue-500 transition font-semibold px-3 py-1 rounded-md">
            Déconnexion
          </button>
        </form>
      @endguest

      {{-- Icône panier --}}
      <button id="show-cart" aria-label="Afficher le panier"
        class="relative text-gray-700 hover:text-blue-500 transition focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 7h11l1.5-7M16 21a1 1 0 100-2 1 1 0 000 2zm-8 0a1 1 0 100-2 1 1 0 000 2z" />
        </svg>
        <span
          class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2"
          id="cart-count">0</span>
      </button>
    </div>
  </div>
</nav>

{{-- Fond flouté pour panier --}}
<div id="cart-overlay" class="fixed inset-0 bg-white bg-opacity-20 backdrop-blur-md z-40 hidden cursor-pointer"></div>

{{-- Fond flouté pour catégories --}}
<div id="categories-overlay" class="fixed inset-0 bg-white bg-opacity-20 backdrop-blur-md z-40 hidden cursor-pointer"></div>

{{-- Panneau latéral du panier (droite) --}}
<aside id="cart-sidebar"
  class="fixed top-0 right-0 h-full w-80 bg-white shadow-lg z-50 p-6 flex flex-col hidden">
  <button id="hide-cart" aria-label="Fermer le panier"
    class="self-end text-gray-600 hover:text-gray-900 text-2xl font-bold">
    &times;
  </button>
  <h2 class="text-2xl font-bold mb-4 text-[#8B4513]">Votre panier</h2>
  <div id="cart-items" class="flex-grow space-y-4 text-gray-700 overflow-auto">
    <p class="text-gray-500">Aucun produit actuellement sélectionné.</p>
  </div>
  <button
    id="checkout-btn"
    onclick="checkout()"
    class="mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition"
    style="user-select: none;"
    >
    Commander
  </button>
</aside>

{{-- Panneau latéral des catégories (gauche) --}}
<aside id="categories-sidebar"
  class="fixed top-0 left-0 h-full w-80 bg-white shadow-lg z-50 p-6 flex flex-col hidden overflow-auto">
  <button id="hide-categories" aria-label="Fermer les catégories"
    class="self-end text-gray-600 hover:text-gray-900 text-2xl font-bold">
    &times;
  </button>
  <h2 class="text-2xl font-bold mb-4 text-[#8B4513]">Catégories</h2>
  <div id="categories-list" class="flex-grow space-y-3 text-gray-700">
    @forelse ($categories as $category)
      <a href="{{ route('category.show', $category->id)  }}" class="block px-3 py-2 rounded hover:bg-[#8B4513] hover:text-white transition">
        {{ $category->nom }}
      </a>
    @empty
      <p class="text-gray-500">Aucune catégorie disponible.</p>
    @endforelse
  </div>
</aside>

{{-- JS d’interaction panier + catégories --}}
<script>
  // PANIER
  const showCartBtn = document.getElementById('show-cart');
  const hideCartBtn = document.getElementById('hide-cart');
  const cartSidebar = document.getElementById('cart-sidebar');
  const cartOverlay = document.getElementById('cart-overlay');

  showCartBtn?.addEventListener('click', () => {
    cartSidebar.classList.remove('hidden');
    cartOverlay.classList.remove('hidden');

    // Si le panneau catégories est ouvert, le fermer
    categoriesSidebar.classList.add('hidden');
    categoriesOverlay.classList.add('hidden');
  });

  hideCartBtn?.addEventListener('click', () => {
    cartSidebar.classList.add('hidden');
    cartOverlay.classList.add('hidden');
  });

  cartOverlay?.addEventListener('click', () => {
    cartSidebar.classList.add('hidden');
    cartOverlay.classList.add('hidden');
  });

  // CATÉGORIES
  const showCategoriesBtn = document.getElementById('show-categories');
  const hideCategoriesBtn = document.getElementById('hide-categories');
  const categoriesSidebar = document.getElementById('categories-sidebar');
  const categoriesOverlay = document.getElementById('categories-overlay');

  showCategoriesBtn?.addEventListener('click', () => {
    categoriesSidebar.classList.remove('hidden');
    categoriesOverlay.classList.remove('hidden');

    // Si le panneau panier est ouvert, le fermer
    cartSidebar.classList.add('hidden');
    cartOverlay.classList.add('hidden');
  });

  hideCategoriesBtn?.addEventListener('click', () => {
    categoriesSidebar.classList.add('hidden');
    categoriesOverlay.classList.add('hidden');
  });

  categoriesOverlay?.addEventListener('click', () => {
    categoriesSidebar.classList.add('hidden');
    categoriesOverlay.classList.add('hidden');
  });

  // PANIER LOGIQUE EXISTANTE
  window.cart = JSON.parse(localStorage.getItem('cart')) || [];

  function saveCart() {
    localStorage.setItem('cart', JSON.stringify(window.cart));
  }

  function addToCart(id, name, price, image = null) {
    const qtyInput = document.getElementById(`qty-${id}`);
    const qty = qtyInput ? parseInt(qtyInput.value) : 1;
    const existing = window.cart.find(item => item.id === id);
    if (existing) {
      existing.qty += qty;
    } else {
      window.cart.push({ id, name, price, qty, image });
    }
    saveCart();
    updateCartUI();
  }

  function removeFromCart(id) {
    window.cart = window.cart.filter(item => item.id !== id);
    saveCart();
    updateCartUI();
  }

  function updateCartUI() {
    const cartContainer = document.getElementById('cart-items');
    const badge = document.getElementById('cart-count');
    let totalItems = 0;

    if (window.cart.length === 0) {
      cartContainer.innerHTML = `<p class="text-gray-500">Aucun produit actuellement sélectionné.</p>`;
      badge.textContent = 0;
      document.getElementById('checkout-btn').disabled = true;
      return;
    }

    document.getElementById('checkout-btn').disabled = false;

    let html = '';
    window.cart.forEach(item => {
      totalItems += item.qty;
      html += `
        <div class="flex items-center justify-between border-b pb-2">
          <div class="flex items-center space-x-3">
            <img src="${item.image ?? '/images/default.jpg'}" alt="${item.name}" class="w-14 h-14 object-cover rounded">
            <div>
              <p class="font-semibold">${item.name}</p>
              <p class="text-sm text-gray-500">x${item.qty} - ${(item.price * item.qty).toFixed(2)} Frcfa</p>
            </div>
          </div>
          <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700 text-lg">🗑️</button>
        </div>
      `;
    });

    cartContainer.innerHTML = html;
    badge.textContent = totalItems;
  }

  // Exposer les fonctions globalement
  window.addToCart = addToCart;
  window.removeFromCart = removeFromCart;

  // Fonction à appeler au clic sur Commander
  function checkout() {
    if(window.cart.length === 0) {
      alert("Votre panier est vide.");
      return;
    }
    // Redirection vers la page commande
    window.location.href = "{{ route('order.index') }}";
  }

  // Initialiser l'affichage
  updateCartUI();
</script>
