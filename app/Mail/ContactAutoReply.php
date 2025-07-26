<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public $nom;
    public $message;

    public function __construct($nom, $message = null)
    {
        $this->nom = $nom;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('Merci pour votre contact !')
            ->view('emails.contact_auto_reply')
            ->with([
                'nom' => $this->nom,
                'message' => $this->message,
            ]);
    }
} 