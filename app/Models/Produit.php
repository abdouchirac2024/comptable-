<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_id',
        'nom',
        'nom_en',
        'description_courte',
        'description_courte_en',
        'description_longue',
        'description_longue_en',
        'stock',
        'est_en_vedette',
        'prix',
        'reference_sku',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produit) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $produit->nom_en = $translator->translate($produit->nom);
            if ($produit->description_courte) {
                $produit->description_courte_en = $translator->translate($produit->description_courte);
            }
            if ($produit->description_longue) {
                $produit->description_longue_en = $translator->translate($produit->description_longue);
            }
        });

        static::updating(function ($produit) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $produit->nom_en = $translator->translate($produit->nom);
            if ($produit->description_courte) {
                $produit->description_courte_en = $translator->translate($produit->description_courte);
            }
            if ($produit->description_longue) {
                $produit->description_longue_en = $translator->translate($produit->description_longue);
            }
        });
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'ligne_commandes')
                    ->withPivot('quantite', 'prix_unitaire_snapshot')
                    ->withTimestamps();
    }
}
