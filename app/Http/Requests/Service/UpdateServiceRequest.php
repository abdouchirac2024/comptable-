<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $serviceId = $this->route('id') ?? $this->route('service');
        return [
            'nom' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:services,slug,' . $serviceId,
            'description' => 'sometimes|string',
            'categorie' => 'sometimes|string|max:255',
            'tarif' => 'sometimes|nullable|string|max:255',
            'duree' => 'sometimes|nullable|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }
} 