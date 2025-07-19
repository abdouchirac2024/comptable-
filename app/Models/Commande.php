<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_commande',
        'statut',
        'total_commande',
        'adresse_livraison_snapshot',
        'adresse_livraison_snapshot_en',
        'adresse_facturation_snapshot',
        'adresse_facturation_snapshot_en',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($commande) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($commande->adresse_livraison_snapshot) {
                $commande->adresse_livraison_snapshot_en = $translator->translate($commande->adresse_livraison_snapshot);
            }
            if ($commande->adresse_facturation_snapshot) {
                $commande->adresse_facturation_snapshot_en = $translator->translate($commande->adresse_facturation_snapshot);
            }
        });

        static::updating(function ($commande) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($commande->adresse_livraison_snapshot) {
                $commande->adresse_livraison_snapshot_en = $translator->translate($commande->adresse_livraison_snapshot);
            }
            if ($commande->adresse_facturation_snapshot) {
                $commande->adresse_facturation_snapshot_en = $translator->translate($commande->adresse_facturation_snapshot);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}
