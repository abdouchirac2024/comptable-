<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvisResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'fr');
        return [
            'id' => $this->id,
            'produit_id' => $this->produit_id,
            'user_id' => $this->user_id,
            'titre' => $lang === 'en' ? $this->titre_en : $this->titre,
            'titre_en' => $this->titre_en,
            'commentaire' => $lang === 'en' ? $this->commentaire_en : $this->commentaire,
            'commentaire_en' => $this->commentaire_en,
            'est_approuve' => $this->est_approuve,
            'note' => $this->note,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
