<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Produits disponibles') }}
            </h2>

            <button id="openCreateModalBtn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                + Ajouter un produit
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto grid grid-cols-1 gap-4">
            @forelse ($produits as $produit)
                <div class="bg-white shadow-md rounded-lg p-4 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        @if($produit->image)
                            <img src="{{ asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded text-gray-400">No Img</div>
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $produit->nom }}</h3>
                            <p class="text-sm text-gray-600">{{ Str::limit($produit->description, 50) }}</p>
                            <p class="text-sm font-bold text-gray-900">
                                Prix: {{ number_format($produit->prix, 2, ',', ' ') }} Frcfa
                                @if($produit->promo_prix)
                                    <span class="text-red-600 ml-2">Promo: {{ number_format($produit->promo_prix, 2, ',', ' ') }} Frcfa</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">Catégorie: {{ $produit->categorie ? $produit->categorie->nom : 'Aucune' }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-4 text-sm font-medium">
                        <button 
                            onclick="openEditModal('{{ $produit->id }}', '{{ addslashes($produit->nom) }}', '{{ addslashes($produit->description ?? '') }}', '{{ $produit->prix }}', '{{ $produit->promo_prix ?? '' }}', '{{ $produit->stock ?? '' }}', '{{ $produit->categorie_id ?? '' }}')" 
                            class="text-blue-600 hover:underline" title="Modifier"
                        >
                            Modifier
                        </button>

                        <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline" title="Supprimer">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Aucun produit disponible.</p>
            @endforelse
        </div>
    </div>

    <!-- Modal création -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative overflow-y-auto max-h-[90vh]">
            <button id="closeCreateModalBtn" class="absolute top-2 right-2 text-xl text-gray-600 hover:text-red-600">&times;</button>
            <h3 class="text-lg font-bold mb-4">Ajouter un produit</h3>
            <form method="POST" action="{{ route('produits.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" name="nom" placeholder="Nom du produit" required class="w-full border rounded-md p-2 mb-3">
                <textarea name="description" placeholder="Description" class="w-full border rounded-md p-2 mb-3"></textarea>
                <input type="file" name="image" accept="image/*" class="mb-3">
                <input type="number" step="0.01" name="prix" placeholder="Prix" required class="w-full border rounded-md p-2 mb-3">
                <input type="number" step="0.01" name="promo_prix" placeholder="Prix promo (optionnel)" class="w-full border rounded-md p-2 mb-3">
                <input type="number" name="stock" placeholder="Stock (optionnel)" class="w-full border rounded-md p-2 mb-3">

                <select name="categorie_id" class="w-full border rounded-md p-2 mb-3">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                    @endforeach
                </select>

                <select name="boutique_id" required class="w-full border rounded-md p-2 mb-4">
                    <option value="">-- Choisir une boutique --</option>
                    @foreach($boutiques as $boutique)
                        <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
                    @endforeach
                </select>

                <div class="flex justify-end">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal édition -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative overflow-y-auto max-h-[90vh]">
            <button id="closeEditModalBtn" class="absolute top-2 right-2 text-xl text-gray-600 hover:text-red-600">&times;</button>
            <h3 class="text-lg font-bold mb-4">Modifier le produit</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="text" name="nom" id="edit_nom" required class="w-full border rounded-md p-2 mb-3">
                <textarea name="description" id="edit_description" class="w-full border rounded-md p-2 mb-3"></textarea>
                <input type="file" name="image" accept="image/*" class="mb-3">
                <input type="number" step="0.01" name="prix" id="edit_prix" required class="w-full border rounded-md p-2 mb-3">
                <input type="number" step="0.01" name="promo_prix" id="edit_promo_prix" class="w-full border rounded-md p-2 mb-3">
                <input type="number" name="stock" id="edit_stock" class="w-full border rounded-md p-2 mb-3">

                <select name="categorie_id" id="edit_categorie_id" class="w-full border rounded-md p-2 mb-3">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                    @endforeach
                </select>

                <select name="boutique_id" id="edit_boutique_id" required class="w-full border rounded-md p-2 mb-4">
                    <option value="">-- Choisir une boutique --</option>
                    @foreach($boutiques as $boutique)
                        <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
                    @endforeach
                </select>

                <div class="flex justify-end">
                    <button class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Modifier</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');

            document.getElementById('openCreateModalBtn').onclick = () => createModal.classList.remove('hidden');
            document.getElementById('closeCreateModalBtn').onclick = () => createModal.classList.add('hidden');
            document.getElementById('closeEditModalBtn').onclick = () => editModal.classList.add('hidden');

            window.openEditModal = function(id, nom, description, prix, promo_prix, stock, categorie_id) {
                document.getElementById('edit_nom').value = nom;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_prix').value = prix;
                document.getElementById('edit_promo_prix').value = promo_prix || '';
                document.getElementById('edit_stock').value = stock || '';
                document.getElementById('edit_categorie_id').value = categorie_id || '';
                // Pour boutique_id, si besoin, tu peux l'ajouter aussi dans les params

                document.getElementById('editForm').action = `/produits/${id}`;
                editModal.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
