<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class HeroSlideResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'hero_section_id' => $this->hero_section_id,
            'slide_order' => $this->slide_order,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'gradient' => $this->gradient,
            'background_image' => $this->getBackgroundImageUrl(),
            'images' => $this->getImagesUrlsAttribute(), // Images multiples
            'image_alt_text' => $this->image_alt_text,
            'slide_duration' => $this->slide_duration,
            'is_active' => $this->is_active,
            'hero_section' => new HeroSectionResource($this->whenLoaded('heroSection')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Obtenir l'URL de l'image de fond
     */
    private function getBackgroundImageUrl(): ?string
    {
        if (!$this->background_image) {
            return null;
        }

        // Si c'est déjà une URL complète
        if (filter_var($this->background_image, FILTER_VALIDATE_URL)) {
            return $this->background_image;
        }

        // Si c'est un chemin relatif, construire l'URL
        if (Storage::disk('public')->exists($this->background_image)) {
            return asset('storage/' . $this->background_image);
        }

        return null;
    }
} 