<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_section_id',
        'slide_order',
        'title',
        'subtitle',
        'description',
        'gradient',
        'background_image',
        'slide_duration',
        'is_active',
    ];

    protected $casts = [
        'slide_order' => 'integer',
        'slide_duration' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec la section hero
     */
    public function heroSection()
    {
        return $this->belongsTo(HeroSection::class);
    }

    /**
     * Scope pour les slides actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour ordonner par slide_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('slide_order');
    }

    /**
     * Obtenir l'URL de l'image de fond
     */
    public function getBackgroundImageUrlAttribute()
    {
        if (!$this->background_image) {
            return null;
        }

        // Si c'est déjà une URL complète
        if (filter_var($this->background_image, FILTER_VALIDATE_URL)) {
            return $this->background_image;
        }

        // Si c'est un chemin relatif, construire l'URL
        if (\Storage::disk('public')->exists($this->background_image)) {
            return asset('storage/' . $this->background_image);
        }

        return null;
    }
} 