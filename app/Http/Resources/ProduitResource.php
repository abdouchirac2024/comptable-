<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'fr');
        return [
            'id' => $this->id,
            'categorie_id' => $this->categorie_id,
            'nom' => $lang === 'en' ? $this->nom_en : $this->nom,
            'nom_en' => $this->nom_en,
            'description_courte' => $lang === 'en' ? $this->description_courte_en : $this->description_courte,
            'description_courte_en' => $this->description_courte_en,
            'description_longue' => $lang === 'en' ? $this->description_longue_en : $this->description_longue,
            'description_longue_en' => $this->description_longue_en,
            'stock' => $this->stock,
            'est_en_vedette' => $this->est_en_vedette,
            'prix' => $this->prix,
            'reference_sku' => $this->reference_sku,
            'slug' => $this->slug,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
