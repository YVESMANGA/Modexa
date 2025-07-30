<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client');
            $table->string('telephone');
            $table->string('adresse')->nullable();
            $table->decimal('total', 10, 2);
            
            // Mode de paiement: 'en_ligne' ou 'a_la_livraison'
            $table->enum('mode_paiement', ['en_ligne', 'a_la_livraison']);
        
            // Mode de livraison: 'a_domicile' ou 'retrait_boutique'
            $table->enum('mode_livraison', ['a_domicile', 'retrait_boutique']);
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
