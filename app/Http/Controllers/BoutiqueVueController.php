<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use Illuminate\Http\Request;

class BoutiqueVueController extends Controller
{
    public function show(Boutique $boutique)
    {
        // Charge la boutique + ses produits (avec relation)
        $boutique->load('produits');

        return view('boutique', compact('boutique'));
    }


    public function indexAll()
{
    $boutiques = Boutique::latest()->paginate(12); // Affiche toutes les boutiques, paginées
    return view('boutiques', compact('boutiques'));
}

}
