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
        'adresse_facturation_snapshot',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'ligne_commandes')
                    ->withPivot('quantite', 'prix_unitaire_snapshot')
                    ->withTimestamps();
    }
    
    // Si vous souhaitez accéder au modèle LigneCommande directement
    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}