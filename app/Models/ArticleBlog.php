<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleBlog extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'image',
        'meta_titre',
        'meta_description',
        'slug',
        'date_publication',
        'user_id',
    ];

    protected $casts = [
        'date_publication' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $article->titre_en = $translator->translate($article->titre);
            if ($article->contenu) {
                $article->contenu_en = $translator->translate($article->contenu);
            }
        });

        static::updating(function ($article) {
            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
            $translator->setSource('fr');
            $article->titre_en = $translator->translate($article->titre);
            if ($article->contenu) {
                $article->contenu_en = $translator->translate($article->contenu);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
