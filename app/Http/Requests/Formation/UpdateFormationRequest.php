<?php

namespace App\Http\Requests\Formation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'duree' => 'sometimes|nullable|string',
            'tarif' => 'sometimes|nullable|string',
            'slug' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }
} 