<?php
namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommandeConfirmee extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $produits;

    public function __construct($commande, $produits)
    {
        $this->commande = $commande;
        $this->produits = $produits;
    }

    public function build()
    {
        return $this->subject('Nouvelle commande sur Modexa')
                    ->view('emails.commande.confirmee');
    }
}
