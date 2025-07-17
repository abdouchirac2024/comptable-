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
        'commentaire',
        'est_approuve',
        'note',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}