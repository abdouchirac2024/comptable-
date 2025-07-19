<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'sujet_en',
        'message',
        'message_en',
        'est_lu',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contact) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($contact->sujet) {
                $contact->sujet_en = $translator->translate($contact->sujet);
            }
            if ($contact->message) {
                $contact->message_en = $translator->translate($contact->message);
            }
        });

        static::updating(function ($contact) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($contact->sujet) {
                $contact->sujet_en = $translator->translate($contact->sujet);
            }
            if ($contact->message) {
                $contact->message_en = $translator->translate($contact->message);
            }
        });
    }
}
