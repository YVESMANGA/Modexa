<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Commandes enregistrées') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <!-- Filtres -->
        <div class="max-w-4xl mx-auto mb-6">
            <form method="GET" action="{{ route('commande.index') }}" class="flex flex-wrap gap-4 items-center bg-white p-4 rounded-lg shadow-md">
                <div>
                    <label for="mois" class="block text-sm font-medium text-gray-700">Mois</label>
                    <input type="month" name="mois" id="mois" value="{{ request('mois') }}" class="border border-gray-300 rounded-md p-2">
                </div>

                <div>
                    <label for="boutique" class="block text-sm font-medium text-gray-700">Boutique</label>
                    <select name="boutique" id="boutique" class="border border-gray-300 rounded-md p-2">
                        <option value="">Toutes</option>
                        @foreach ($boutiques as $boutique)
                            <option value="{{ $boutique->id }}" @selected(request('boutique') == $boutique->id)>
                                {{ $boutique->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="self-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtrer</button>
                </div>

                <div class="self-end">
    <a href="{{ route('commande.export', ['mois' => request('mois'), 'boutique' => request('boutique')]) }}"
       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Exporter en CSV
    </a>
</div>


                <!-- 🟡 Compteur de commandes -->
                <div class="ml-auto text-sm text-gray-700 mt-2 sm:mt-0">
                    <span class="font-semibold">{{ $nbCommandes }}</span> commande(s) trouvée(s)
                </div>
            </form>
        </div>

        <!-- Liste des commandes -->
        <div class="max-w-4xl mx-auto grid grid-cols-1 gap-4">
            @forelse ($commandes as $commande)
                @php
                    $detailsFiltres = isset($boutiqueId)
                        ? $commande->details->filter(fn($d) => $d->produit && $d->produit->boutique_id == $boutiqueId)
                        : $commande->details;

                    $totalFiltré = $detailsFiltres->sum(fn($d) => $d->prix_unitaire * $d->quantite);
                @endphp

                @if($detailsFiltres->isNotEmpty())
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">{{ $commande->nom_client }}</p>
                                <p class="text-sm text-gray-600">Téléphone : {{ $commande->telephone }}</p>
                                <p class="text-sm text-gray-600">Adresse : {{ $commande->adresse ?? '—' }}</p>
                                <p class="text-sm text-gray-600">
                                    Total pour cette boutique :
                                    <span class="font-bold text-green-600">{{ number_format($totalFiltré, 2) }} FCFA</span>
                                </p>
                                <p class="text-sm text-gray-500">
                                    Passée le : {{ $commande->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Produits commandés :</p>
                            <ul class="space-y-1 text-sm text-gray-600 list-disc list-inside">
                                @foreach($detailsFiltres as $detail)
                                    @php $produit = $detail->produit; @endphp
                                    <li>
                                        {{ $produit->nom ?? 'Produit supprimé' }} × {{ $detail->quantite }}
                                        ({{ number_format($detail->prix_unitaire, 2) }} FCFA/u)
                                        <br>
                                        <span class="text-xs text-gray-500 italic">
                                            Boutique : {{ $produit->boutique->nom ?? 'Inconnue' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-center text-gray-500">Aucune commande enregistrée.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
