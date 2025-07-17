<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'methode',
        'transaction_id',
        'montant',
        'statut',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}