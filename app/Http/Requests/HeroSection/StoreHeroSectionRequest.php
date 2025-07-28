<?php

namespace App\Http\Requests\HeroSection;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'is_active.boolean' => 'Le statut actif doit être un booléen.',
        ];
    }
} 