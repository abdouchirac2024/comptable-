<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'type' => $this->type,
            'date_realisation' => $this->date_realisation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 