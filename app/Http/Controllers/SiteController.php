<?php
// app/Http/Controllers/SiteController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Boutique;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\CommandeDetail;
use Illuminate\Support\Facades\Session;

class SiteController extends Controller
{
    // Accueil : liste des produits
    public function accueil()
    {
    $boutiques = Boutique::latest()->get();
    $produits = Produit::latest()->get(); 
    $categories = Categorie::latest()->get(); 
   

    return view('acceuil', compact('produits', 'boutiques','categories'));
    }

    // Liste des boutiques
    public function listeBoutiques()
    {
        $boutiques = Boutique::where('actif', true)->get();
        return view('boutiques', compact('boutiques'));
    }

    // Produits d'une boutique
    public function voirBoutique($id)
    {
        $boutique = Boutique::findOrFail($id);
        $produits = $boutique->produits()->where('actif', true)->get();
        return view('boutique', compact('boutique', 'produits'));
    }

    // Page produit
    public function voirProduit($id)
    {
        $produit = Produit::findOrFail($id);
        return view('produit', compact('produit'));
    }

    // Panier
    public function panier()
    {
        $panier = Session::get('panier', []);
        return view('panier', compact('panier'));
    }

    // Ajouter au panier
    public function ajouterAuPanier(Request $request)
    {
        $id = $request->produit_id;
        $quantite = $request->quantite ?? 1;

        $produit = Produit::findOrFail($id);

        $panier = Session::get('panier', []);

        if (isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantite;
        } else {
            $panier[$id] = [
                'nom' => $produit->nom,
                'prix' => $produit->promo_prix ?? $produit->prix,
                'quantite' => $quantite,
                'image' => $produit->image,
            ];
        }

        Session::put('panier', $panier);

        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    // Formulaire de commande
    public function commandeForm()
    {
        $panier = Session::get('panier', []);
        return view('commande', compact('panier'));
    }

    // Valider la commande
    public function validerCommande(Request $request)
    {
        $request->validate([
            'nom_client' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        $panier = Session::get('panier', []);
        if (empty($panier)) {
            return redirect()->back()->with('error', 'Votre panier est vide.');
        }

        $total = collect($panier)->reduce(function ($carry, $item) {
            return $carry + ($item['prix'] * $item['quantite']);
        }, 0);

        $commande = Commande::create([
            'nom_client' => $request->nom_client,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'total' => $total,
        ]);

        foreach ($panier as $produitId => $item) {
            CommandeDetail::create([
                'commande_id' => $commande->id,
                'produit_id' => $produitId,
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix'],
            ]);
        }

        // Envoi de SMS ici (ex : Twilio, Infobip, etc.)
        // Http::post(...)

        Session::forget('panier');

        return redirect()->route('merci')->with('success', 'Commande passée avec succès !');
    }

    // Page de remerciement
    public function merci()
    {
        return view('merci');
    }
}
