<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LigneCommandeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'commande_id' => $this->commande_id,
            'produit_id' => $this->produit_id,
            'produit' => new \App\Http\Resources\ProduitResource($this->whenLoaded('produit')),
            'quantite' => $this->quantite,
            'prix_unitaire_snapshot' => $this->prix_unitaire_snapshot,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
