<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    // Laravel protège par défaut les tables contre le mass-assignment
    // en utilisant un nom de table pluriel, ici "avis" est déjà pluriel,
    // mais pour être explicite on peut le définir.
    protected $table = 'avis';

    protected $fillable = [
        'produit_id',
        'user_id',
        'titre',
        'titre_en',
        'commentaire',
        'commentaire_en',
        'est_approuve',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($avis) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($avis->titre) {
                $avis->titre_en = $translator->translate($avis->titre);
            }
            if ($avis->commentaire) {
                $avis->commentaire_en = $translator->translate($avis->commentaire);
            }
        });

        static::updating(function ($avis) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($avis->titre) {
                $avis->titre_en = $translator->translate($avis->titre);
            }
            if ($avis->commentaire) {
                $avis->commentaire_en = $translator->translate($avis->commentaire);
            }
        });
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
