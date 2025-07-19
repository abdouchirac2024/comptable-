<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'numero_commande' => $this->numero_commande,
            'statut' => $this->statut,
            'total_commande' => $this->total_commande,
            'adresse_livraison_snapshot' => $request->header('Accept-Language', 'fr') === 'en' ? $this->adresse_livraison_snapshot_en : $this->adresse_livraison_snapshot,
            'adresse_livraison_snapshot_en' => $this->adresse_livraison_snapshot_en,
            'adresse_facturation_snapshot' => $request->header('Accept-Language', 'fr') === 'en' ? $this->adresse_facturation_snapshot_en : $this->adresse_facturation_snapshot,
            'adresse_facturation_snapshot_en' => $this->adresse_facturation_snapshot_en,
            'lignes' => LigneCommandeResource::collection($this->whenLoaded('lignes')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
