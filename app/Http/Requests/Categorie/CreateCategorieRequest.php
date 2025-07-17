<?php

namespace App\Http\Requests\Categorie;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => __('categories.validation.failed'),
                'errors' => $validator->errors()
            ], 422)
        );
    }
} 