<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleBlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'fr');
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'titre' => $lang === 'en' ? $this->titre_en : $this->titre,
            'titre_en' => $this->titre_en,
            'contenu' => $lang === 'en' ? $this->contenu_en : $this->contenu,
            'contenu_en' => $this->contenu_en,
            'meta_titre' => $this->meta_titre,
            'meta_description' => $this->meta_description,
            'slug' => $this->slug,
            'date_publication' => $this->date_publication?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'image' => $this->image ? asset('storage/' . $this->image) : null,
        ];
    }
}
