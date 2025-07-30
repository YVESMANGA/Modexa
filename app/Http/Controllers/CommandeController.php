<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\CommandeConfirmee;




class CommandeController extends Controller
{
    public function index()
    {
        
        return view('commande');
    }

   
    
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'mode_paiement' => 'required|in:en_ligne,a_la_livraison',
            'mode_livraison' => 'required|in:a_domicile,retrait_boutique',
            'order_data' => 'required|json',
        ]);
    
        $cart = json_decode($request->order_data, true);
    
        if (empty($cart)) {
            return back()->with('error', 'Le panier est vide.');
        }
    
        // Calcul du total
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
    
        // Création de la commande
        $commande = \App\Models\Commande::create([
            'nom_client' => $request->prenom . ' ' . $request->nom,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'total' => $total,
            'mode_paiement' => $request->mode_paiement,
            'mode_livraison' => $request->mode_livraison,
        ]);
    
        // Récupérer les produits liés au panier
        $produits = \App\Models\Produit::whereIn('id', collect($cart)->pluck('id'))->with('boutique')->get()->keyBy('id');
    
        $boutiquesProduits = [];
    
        foreach ($cart as $item) {
            $produit = $produits[$item['id']] ?? null;
            if (!$produit) continue;
    
            $boutiqueId = $produit->boutique_id;
    
            // Enregistrement du détail de la commande
            \App\Models\CommandeDetail::create([
                'commande_id' => $commande->id,
                'produit_id' => $produit->id,
                'quantite' => $item['qty'],
                'prix_unitaire' => $item['price'],
            ]);
    
            $boutiquesProduits[$boutiqueId]['boutique'] = $produit->boutique;
            $boutiquesProduits[$boutiqueId]['produits'][] = [
                'nom' => $produit->nom,
                'quantite' => $item['qty'],
                'prix' => $item['price'],
            ];
        }
    
        // Envoi d'email aux boutiques
        foreach ($boutiquesProduits as $info) {
            $boutique = $info['boutique'];
            $produitsBoutique = $info['produits'];
    
            if (!empty($boutique->email)) {
                Mail::to($boutique->email)->send(new CommandeConfirmee($commande, $produitsBoutique));
            }
        }
    
        return redirect('/')->with('success_commande', true);
    }
    

    
}
