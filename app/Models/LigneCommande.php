<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;
    
    protected $table = 'ligne_commandes'; // Spécifier le nom de la table est une bonne pratique

    protected $fillable = [
        'commande_id',
        'produit_id',
        'quantite',
        'prix_unitaire_snapshot',
    ];
}