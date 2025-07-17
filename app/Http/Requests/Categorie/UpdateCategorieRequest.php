<?php

namespace App\Http\Requests\Categorie;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categorie = $this->route('categorie');
        $categorieId = is_object($categorie) ? $categorie->getKey() : $categorie;
        
        return [
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'nom')->ignore($categorieId, 'id'),
            ],
            'description' => 'nullable|string|max:1000',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categorieId, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('categories.validation.nom.required'),
            'nom.string' => __('categories.validation.nom.string'),
            'nom.max' => __('categories.validation.nom.max'),
            'nom.unique' => __('categories.validation.nom.unique'),
            'description.string' => __('categories.validation.description.string'),
            'description.max' => __('categories.validation.description.max'),
            'slug.string' => __('categories.validation.slug.string'),
            'slug.max' => __('categories.validation.slug.max'),
            'slug.unique' => __('categories.validation.slug.unique'),
        ];
    }
} 