
<h1>Nouvelle commande sur Modexa</h1>
<p><strong>Client :</strong> {{ $commande->nom_client }}</p>
<p><strong>Téléphone :</strong> {{ $commande->telephone }}</p>
<p><strong>Adresse :</strong> {{ $commande->adresse }}</p>

<hr>

<h4>Produits commandés :</h4>
<ul>
  @foreach ($produits as $produit)
    <li>{{ $produit['nom'] }} - x{{ $produit['quantite'] }} - {{ $produit['prix'] * $produit['quantite'] }} €</li>
  @endforeach
</ul>

<p>Total approximatif pour cette boutique : 
  <strong>{{ collect($produits)->sum(fn($p) => $p['quantite'] * $p['prix']) }} €</strong>
</p>

<h4>Methode de Livraison :</h4> 
<p><strong> {{ $commande->mode_livraison }}</strong></p>

<h4>Methode de Paiement :</h4> 
<p><strong> {{ $commande->mode_paiement }}</strong></p>
