<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'fr');
        return [
            'id' => $this->id ?? null,
            'nom' => $lang === 'en' ? ($this->nom_en ?? null) : ($this->nom ?? null),
            'description' => $lang === 'en' ? ($this->description_en ?? null) : ($this->description ?? null),
            'slug' => $this->slug ?? null,
            'produits_count' => $this->whenCounted('produits'),
            'created_at' => $this->created_at?->toISOString() ?? null,
            'updated_at' => $this->updated_at?->toISOString() ?? null,
        ];
    }
}
