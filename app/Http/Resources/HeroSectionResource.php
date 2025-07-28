<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HeroSectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'is_active' => $this->is_active,
            'slides' => HeroSlideResource::collection($this->whenLoaded('slides')),
            'active_slides' => HeroSlideResource::collection($this->whenLoaded('activeSlides')),
            'slides_count' => $this->when(isset($this->slides_count), $this->slides_count),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
} 