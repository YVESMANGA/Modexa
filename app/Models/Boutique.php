<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    //
    protected $fillable = ['nom', 'description', 'logo', 'telephone', 'email'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
