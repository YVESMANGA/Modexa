<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('categorie')->latest()->get();
        $categories = Categorie::all();
        $boutiques = Boutique::all();

        return view('auth.product.produit', compact('produits', 'categories', 'boutiques'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'prix' => 'required|numeric|min:0',
            'promo_prix' => 'nullable|numeric|min:0|lt:prix',
            'stock' => 'nullable|integer|min:0',
            'categorie_id' => 'nullable|exists:categories,id',
            'boutique_id' => 'required|exists:boutiques,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('produits', 'public');
        }

        Produit::create($validated);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'prix' => 'required|numeric|min:0',
            'promo_prix' => 'nullable|numeric|min:0|lt:prix',
            'stock' => 'nullable|integer|min:0',
            'categorie_id' => 'nullable|exists:categories,id',
            'boutique_id' => 'required|exists:boutiques,id',
        ]);

        if ($request->hasFile('image')) {
            // Supprime ancienne image si existante
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            $validated['image'] = $request->file('image')->store('produits', 'public');
        }

        $produit->update($validated);

        return redirect()->route('produits.index')->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(Produit $produit)
    {
        // Supprime image si existante
        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }

        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }
}
