<?php

namespace App\Http\Requests\Partenaire;

use Illuminate\Foundation\Http\FormRequest;

class StorePartenaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ];
    }
} 