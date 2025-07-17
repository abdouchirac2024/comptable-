<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'url_image',
        'texte_alternatif',
        'est_principale',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}