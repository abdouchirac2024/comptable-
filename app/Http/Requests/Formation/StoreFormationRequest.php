<?php

namespace App\Http\Requests\Formation;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:formations,slug',
            'description' => 'required|string',
            'duree' => 'nullable|string|max:255',
            'tarif' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }
} 