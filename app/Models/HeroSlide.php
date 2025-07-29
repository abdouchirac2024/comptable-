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
        'images', // Images multiples
        'image_alt_text', // Texte alternatif
        'slide_duration',
        'is_active',
    ];

    protected $casts = [
        'slide_order' => 'integer',
        'slide_duration' => 'integer',
        'is_active' => 'boolean',
        'images' => 'array', // Cast JSON en array
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

    /**
     * Obtenir toutes les URLs des images multiples
     */
    public function getImagesUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        $urls = [];
        foreach ($this->images as $image) {
            if (is_string($image)) {
                // Image simple (string)
                $urls[] = $this->getImageUrl($image);
            } elseif (is_array($image) && isset($image['path'])) {
                // Image avec métadonnées
                $urls[] = [
                    'url' => $this->getImageUrl($image['path']),
                    'alt_text' => $image['alt_text'] ?? null,
                    'caption' => $image['caption'] ?? null,
                    'display_order' => $image['display_order'] ?? 0,
                ];
            }
        }

        return $urls;
    }

    /**
     * Obtenir l'URL d'une image
     */
    private function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        // Si c'est déjà une URL complète
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // Si c'est un chemin relatif, construire l'URL
        if (\Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }

        return null;
    }

    /**
     * Ajouter une image au slide
     */
    public function addImage($imagePath, $altText = null, $caption = null, $displayOrder = null)
    {
        $images = $this->images ?? [];
        
        if ($displayOrder === null) {
            $displayOrder = count($images);
        }

        $images[] = [
            'path' => $imagePath,
            'alt_text' => $altText,
            'caption' => $caption,
            'display_order' => $displayOrder,
        ];

        $this->update(['images' => $images]);
    }

    /**
     * Supprimer une image du slide
     */
    public function removeImage($imagePath)
    {
        $images = $this->images ?? [];
        
        $images = array_filter($images, function($image) use ($imagePath) {
            if (is_string($image)) {
                return $image !== $imagePath;
            }
            return $image['path'] !== $imagePath;
        });

        $this->update(['images' => array_values($images)]);
    }

    /**
     * Réordonnancer les images
     */
    public function reorderImages($imageOrders)
    {
        $images = $this->images ?? [];
        
        foreach ($images as $index => $image) {
            if (is_string($image)) {
                $images[$index] = [
                    'path' => $image,
                    'alt_text' => null,
                    'caption' => null,
                    'display_order' => $imageOrders[$index] ?? $index,
                ];
            } else {
                $images[$index]['display_order'] = $imageOrders[$index] ?? $index;
            }
        }

        // Trier par display_order
        usort($images, function($a, $b) {
            return $a['display_order'] <=> $b['display_order'];
        });

        $this->update(['images' => $images]);
    }
} 