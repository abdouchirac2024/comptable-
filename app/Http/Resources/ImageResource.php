<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'fr');
        return [
            'id' => $this->id,
            'produit_id' => $this->produit_id,
            'url_image' => $this->url_image,
            'description' => $lang === 'en' ? $this->description_en : $this->Description,
            'description_en' => $this->description_en,
            'est_principale' => $this->est_principale,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
