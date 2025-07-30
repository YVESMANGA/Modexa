<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    //
    protected $fillable = ['nom_client', 'telephone', 'adresse', 'total','mode_paiement',
    'mode_livraison'];

    public function details()
    {
        return $this->hasMany(CommandeDetail::class);
    }
}
