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
        'Description',
        'description_en',
        'est_principale',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($image->Description) {
                $image->description_en = $translator->translate($image->Description);
            }
        });

        static::updating(function ($image) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            if ($image->Description) {
                $image->description_en = $translator->translate($image->Description);
            }
        });
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
