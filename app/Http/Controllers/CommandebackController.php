<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Commande;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CommandebackController extends Controller
{
    // 🟢 Liste des commandes
    public function index(Request $request)
    {
        $query = Commande::with(['details.produit.boutique'])->latest();

        // 🔷 Filtrer par mois (yyyy-mm)
        if ($request->filled('mois')) {
            $mois = Carbon::parse($request->mois);
            $query->whereMonth('created_at', $mois->month)->whereYear('created_at', $mois->year);
        }

        // 🔷 Filtrer par boutique
        $boutiqueId = null;
        if ($request->filled('boutique')) {
            $boutiqueId = $request->boutique;
            // on filtre uniquement les commandes contenant des produits de cette boutique
            $query->whereHas('details.produit', function ($q) use ($boutiqueId) {
                $q->where('boutique_id', $boutiqueId);
            });
        }

        // 🔸 Récupérer les commandes filtrées
        $commandes = $query->get();

        return view('auth.commande.commande', [
            'commandes' => $commandes,
            'boutiques' => Boutique::all(),
            'nbCommandes' => $commandes->count(),
            'boutiqueId' => $boutiqueId,
        ]);
    }


    public function export(Request $request)
    {
        $mois = $request->mois;
        $boutiqueId = $request->boutique;
    
        $query = Commande::with(['details.produit.boutique'])->latest();
    
        if ($mois) {
            $moisCarbon = \Carbon\Carbon::parse($mois);
            $query->whereMonth('created_at', $moisCarbon->month)
                  ->whereYear('created_at', $moisCarbon->year);
        }
    
        if ($boutiqueId) {
            $query->whereHas('details.produit', function ($q) use ($boutiqueId) {
                $q->where('boutique_id', $boutiqueId);
            });
        }
    
        $commandes = $query->get();
    
        $filename = "commandes_" . date('Ymd_His') . ".csv";
    
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
    
        $columns = [
            'Client', 'Téléphone', 'Adresse', 'Date',
            'Produit', 'Quantité', 'Prix Unitaire', 'Total Produit', 'Boutique'
        ];
    
        $callback = function() use ($commandes, $boutiqueId, $columns, $mois) {
            $file = fopen('php://output', 'w');
    
            // Ligne du filtre mois en entête
            if ($mois) {
                $ligneMois = ['Mois filtré : ' . \Carbon\Carbon::parse($mois)->format('m/Y')];
            } else {
                $ligneMois = ['Mois filtré : Tous'];
            }
            fputcsv($file, $ligneMois);
    
            // Ligne vide après
            fputcsv($file, []);
    
            // Colonnes
            fputcsv($file, $columns);
    
            foreach ($commandes as $commande) {
                $details = $boutiqueId
                    ? $commande->details->filter(fn($d) => $d->produit && $d->produit->boutique_id == $boutiqueId)
                    : $commande->details;
    
                foreach ($details as $detail) {
                    // Préfixe apostrophe pour date (évite les ####)
                    $dateFormatee = "'" . $commande->created_at->format('d/m/Y H:i');
    
                    $row = [
                        $commande->nom_client,
                        $commande->telephone,
                        $commande->adresse,
                        $dateFormatee,
                        $detail->produit->nom ?? 'Produit supprimé',
                        $detail->quantite,
                        $detail->prix_unitaire,
                        $detail->quantite * $detail->prix_unitaire,
                        $detail->produit->boutique->nom ?? 'Inconnue',
                    ];
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

}
