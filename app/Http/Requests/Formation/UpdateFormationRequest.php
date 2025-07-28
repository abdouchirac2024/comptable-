<?php

namespace App\Http\Requests\Formation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFormationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $formationId = $this->route('formation') ?? $this->route('id');
        
        return [
            'nom' => [
                'sometimes',
                'string',
                'max:255',
                'min:2'
            ],
            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000'
            ],
            'duree' => [
                'sometimes',
                'nullable',
                'string',
                'max:100'
            ],
            'tarif' => [
                'sometimes',
                'nullable',
                'string',
                'max:50'
            ],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'min:2',
                Rule::unique('formations')->ignore($formationId)
            ],
            'image' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:5120' // 5MB max
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la formation est requis.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
            
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            
            'duree.string' => 'La durée doit être une chaîne de caractères.',
            'duree.max' => 'La durée ne peut pas dépasser 100 caractères.',
            
            'tarif.string' => 'Le tarif doit être une chaîne de caractères.',
            'tarif.max' => 'Le tarif ne peut pas dépasser 50 caractères.',
            
            'slug.required' => 'Le slug est requis.',
            'slug.string' => 'Le slug doit être une chaîne de caractères.',
            'slug.max' => 'Le slug ne peut pas dépasser 255 caractères.',
            'slug.min' => 'Le slug doit contenir au moins 2 caractères.',
            'slug.unique' => 'Ce slug existe déjà.',
            
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format : jpg, jpeg, png, gif ou webp.',
            'image.max' => 'L\'image ne peut pas dépasser 5MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Nettoyer les données avant validation - seulement si elles ne sont pas vides
        $data = [];
        
        if ($this->has('nom') && !empty($this->nom)) {
            $data['nom'] = trim($this->nom);
        }
        
        if ($this->has('description') && $this->description !== null) {
            $data['description'] = trim($this->description);
        }
        
        if ($this->has('duree') && !empty($this->duree)) {
            $data['duree'] = trim($this->duree);
        }
        
        if ($this->has('tarif') && !empty($this->tarif)) {
            $data['tarif'] = trim($this->tarif);
        }
        
        if ($this->has('slug') && !empty($this->slug)) {
            $data['slug'] = trim($this->slug);
        }
        
        if (!empty($data)) {
            $this->merge($data);
        }
    }
} 