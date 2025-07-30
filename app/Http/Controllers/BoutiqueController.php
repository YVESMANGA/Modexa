<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\Boutique;
use Illuminate\Http\Request;

class BoutiqueController extends Controller
{
    public function index()
    {
        $boutiques = Boutique::latest()->get();
        return view('auth.shop.shop', compact('boutiques'));
    }


    public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'telephone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'logo' => 'nullable|image|max:2048', // max 2Mo
    ]);

    $data = $request->only(['nom', 'description', 'telephone', 'email']);

    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('logos', 'public');
    }

    Boutique::create($data);

    return redirect()->route('shop')->with('success', 'Boutique créée avec succès.');
}


    public function edit(Boutique $boutique)
    {
        return view('boutiques.edit', compact('boutique'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'telephone' => 'nullable|string',
        'email' => 'nullable|email',
        'logo' => 'nullable|image',
    ]);

    $boutique = Boutique::findOrFail($id);

    $boutique->nom = $request->nom;
    $boutique->description = $request->description;
    $boutique->telephone = $request->telephone;
    $boutique->email = $request->email;

    if ($request->hasFile('logo')) {
        $boutique->logo = $request->file('logo')->store('logos', 'public');
    }

    $boutique->save();

    return redirect()->route('shop')->with('success', 'Boutique mise à jour avec succès.');
}


    public function destroy($id)
    {
        $boutique = Boutique::findOrFail($id);
    
    // Suppression du logo si existant
    if ($boutique->logo && Storage::exists('public/' . $boutique->logo)) {
        Storage::delete('public/' . $boutique->logo);
    }

    $boutique->delete();

    return redirect()->route('shop')->with('success', 'Boutique supprimée avec succès.');
    }
}
