<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Catégories disponibles') }}
            </h2>

            <button id="openModalBtn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                + Ajouter une catégorie
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto grid grid-cols-1 gap-4">
            @forelse ($categories as $categorie)
                <div class="bg-white shadow-md rounded-lg p-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-semibold text-gray-800">{{ $categorie->nom }}</span>
                    </div>
                    <div class="flex space-x-4 text-sm font-medium">
                        <button 
                            onclick="openEditModal('{{ $categorie->id }}', '{{ addslashes($categorie->nom) }}', '{{ $categorie->image }}')" 
                            class="text-blue-600 hover:underline"
                            title="Modifier"
                        >
                            Modifier
                        </button>
                        <form action="{{ route('categories.destroy', $categorie->id) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline" title="Supprimer">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Aucune catégorie disponible.</p>
            @endforelse
        </div>
    </div>

    <!-- Modal création -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
            <button id="closeModalBtn" class="absolute top-2 right-2 text-xl text-gray-600 hover:text-red-600">&times;</button>
            <h3 class="text-lg font-bold mb-4">Ajouter une catégorie</h3>
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" name="nom" placeholder="Nom de la catégorie" required class="w-full border rounded-md p-2 mb-4">
                <input type="file" name="image" accept="image/*" class="w-full border rounded-md p-2 mb-4">
                <div class="flex justify-end">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal édition -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
            <button id="closeEditModalBtn" class="absolute top-2 right-2 text-xl text-gray-600 hover:text-red-600">&times;</button>
            <h3 class="text-lg font-bold mb-4">Modifier la catégorie</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="text" name="nom" id="edit_nom" required class="w-full border rounded-md p-2 mb-4">
                <input type="file" name="image" accept="image/*" class="w-full border rounded-md p-2 mb-4">
                <img id="editPreview" src="" alt="Image actuelle" class="w-24 h-24 object-cover mb-2 rounded shadow" style="display: none;" />
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
            const preview = document.getElementById('editPreview');

            document.getElementById('openModalBtn').onclick = () => createModal.classList.remove('hidden');
            document.getElementById('closeModalBtn').onclick = () => createModal.classList.add('hidden');
            document.getElementById('closeEditModalBtn').onclick = () => editModal.classList.add('hidden');

            window.openEditModal = function(id, nom, image = null) {
                document.getElementById('edit_nom').value = nom;
                document.getElementById('editForm').action = `/categories/${id}`;

                if (image) {
                    preview.src = `/storage/categories/${image}`;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }

                editModal.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
