<?php


namespace App\Http\Controllers;


use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    //
    

    public function index()
{
    $categories = Categorie::latest()->get();
    return view('auth.categorie.categorie', compact('categories'));
}

public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $data = $request->only('nom');

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('categories', 'public');
    }

    Categorie::create($data);

    return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
}


public function update(Request $request, $id)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $categorie = Categorie::findOrFail($id);

    // Met à jour le nom
    $categorie->nom = $request->nom;

    // Gère l’image si une nouvelle est envoyée
    if ($request->hasFile('image')) {
        // Supprime l'ancienne image si elle existe
        if ($categorie->image && Storage::disk('public')->exists($categorie->image)) {
            Storage::disk('public')->delete($categorie->image);
        }

        // Enregistre la nouvelle image
        $categorie->image = $request->file('image')->store('categories', 'public');
    }

    $categorie->save();

    return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour.');
}

public function destroy($id)
{
    $categorie = Categorie::findOrFail($id);
    $categorie->delete();
    return redirect()->route('categories.index')->with('success', 'Catégorie supprimée.');
}


public function show($id)
{
    $categorie = Categorie::with('produits')->findOrFail($id);
    return view('categorie', compact('categorie'));
}

}
