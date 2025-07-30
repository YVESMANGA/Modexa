<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Boutiques disponibles') }}
            </h2>

            <button id="openModalBtn" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                + Créer une boutique
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($boutiques as $boutique)
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 relative">
    @if ($boutique->logo)
        <img src="{{ asset('storage/' . $boutique->logo) }}" alt="{{ $boutique->nom }}" class="w-full h-48 object-cover rounded">
    @else
        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 rounded">
            Pas de logo
        </div>
    @endif

    <div class="mt-4">
        <div class="flex items-start justify-between">
            <h3 class="text-lg font-bold text-gray-800">{{ $boutique->nom }}</h3>
            <div class="flex flex-col items-end space-y-2">
                <!-- Bouton Modifier -->
<button 
    class="text-blue-600 hover:underline text-sm font-medium"
    onclick="openEditModal('{{ $boutique->id }}', '{{ addslashes($boutique->nom) }}', '{{ addslashes($boutique->description) }}', '{{ addslashes($boutique->telephone) }}', '{{ addslashes($boutique->email) }}')"
>
    Modifier
</button>

<!-- Bouton Supprimer -->
<form action="{{ route('boutiques.destroy', $boutique->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette boutique ?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
        Supprimer
    </button>
</form>

            </div>
        </div>

        <p class="text-gray-700 mt-1">{{ $boutique->description }}</p>
        <p class="text-gray-600 mt-2"><strong>Téléphone :</strong> {{ $boutique->telephone ?? 'N/A' }}</p>
        <p class="text-gray-600"><strong>Email :</strong> {{ $boutique->email ?? 'N/A' }}</p>
    </div>
</div>

            @empty
                <div class="col-span-full text-center text-gray-500">
                    Aucune boutique disponible pour le moment.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Création -->
    <div id="createModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
            <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>

            <h3 class="text-lg font-bold mb-4 text-gray-800">Créer une nouvelle boutique</h3>

            <form action="{{ route('boutiques.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="nom" id="nom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" name="logo" id="logo" class="mt-1 block w-full text-sm">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edition -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
            <button id="closeEditModalBtn" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>

            <h3 class="text-lg font-bold mb-4 text-gray-800">Modifier la boutique</h3>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="edit_id">

                <div class="mb-4">
                    <label for="edit_nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="nom" id="edit_nom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="edit_description" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <div class="mb-4">
                    <label for="edit_telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="telephone" id="edit_telephone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="edit_email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label for="edit_logo" class="block text-sm font-medium text-gray-700">Logo (laisser vide pour garder l'actuel)</label>
                    <input type="file" name="logo" id="edit_logo" class="mt-1 block w-full text-sm">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-4 py-2 rounded">
                        Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createModal = document.getElementById('createModal');
            const openCreateBtn = document.getElementById('openModalBtn');
            const closeCreateBtn = document.getElementById('closeModalBtn');

            const editModal = document.getElementById('editModal');
            const closeEditBtn = document.getElementById('closeEditModalBtn');

            openCreateBtn.addEventListener('click', () => createModal.classList.remove('hidden'));
            closeCreateBtn.addEventListener('click', () => createModal.classList.add('hidden'));
            closeEditBtn.addEventListener('click', () => editModal.classList.add('hidden'));

            window.openEditModal = function(id, nom, description, telephone, email) {
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nom').value = nom;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_telephone').value = telephone;
                document.getElementById('edit_email').value = email;

                document.getElementById('editForm').action = `/shop/${id}`;
                editModal.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
