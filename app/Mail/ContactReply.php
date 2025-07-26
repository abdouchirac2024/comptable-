<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public $nom;
    public $reponse;

    public function __construct($nom, $reponse)
    {
        $this->nom = $nom;
        $this->reponse = $reponse;
    }

    public function build()
    {
        return $this->subject('Réponse à votre message')
            ->view('emails.contact_reply')
            ->with([
                'nom' => $this->nom,
                'reponse' => $this->reponse,
            ]);
    }
} 