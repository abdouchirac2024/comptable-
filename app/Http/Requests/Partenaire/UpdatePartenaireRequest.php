<?php

namespace App\Http\Requests\Partenaire;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartenaireRequest extends FormRequest
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
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }
} 