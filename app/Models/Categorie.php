<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'nom_en', 'description', 'description_en', 'slug'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($categorie) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $categorie->nom_en = $translator->translate($categorie->nom);
            if ($categorie->description) {
                $categorie->description_en = $translator->translate($categorie->description);
            }
        });

        static::updating(function ($categorie) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $categorie->nom_en = $translator->translate($categorie->nom);
            if ($categorie->description) {
                $categorie->description_en = $translator->translate($categorie->description);
            }
        });
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
