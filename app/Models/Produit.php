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
        'description_courte',
        'description_longue',
        'stock',
        'est_en_vedette',
        'prix',
        'reference_sku',
        'slug',
    ];

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