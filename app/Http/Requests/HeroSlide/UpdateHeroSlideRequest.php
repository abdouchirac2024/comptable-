<?php

namespace App\Http\Requests\HeroSlide;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_section_id' => 'sometimes|exists:hero_sections,id',
            'slide_order' => 'sometimes|integer|min:0',
            'title' => 'sometimes|string|max:255|min:2',
            'subtitle' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'gradient' => 'sometimes|nullable|string|max:255',
            'background_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'images_upload.*' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120', // Images multiples
            'image_alt_text' => 'sometimes|nullable|string|max:255',
            'slide_duration' => 'sometimes|integer|min:1000|max:30000',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'hero_section_id.exists' => 'La section Hero spécifiée n\'existe pas.',
            'slide_order.integer' => 'L\'ordre du slide doit être un entier.',
            'slide_order.min' => 'L\'ordre du slide doit être au moins 0.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'title.min' => 'Le titre doit contenir au moins 2 caractères.',
            'subtitle.string' => 'Le sous-titre doit être une chaîne de caractères.',
            'subtitle.max' => 'Le sous-titre ne peut pas dépasser 255 caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'gradient.string' => 'Le gradient doit être une chaîne de caractères.',
            'gradient.max' => 'Le gradient ne peut pas dépasser 255 caractères.',
            'background_image.image' => 'Le fichier doit être une image.',
            'background_image.mimes' => 'L\'image doit être au format : jpg, jpeg, png, gif ou webp.',
            'background_image.max' => 'L\'image ne peut pas dépasser 5MB.',
            'images_upload.*.image' => 'Chaque fichier doit être une image.',
            'images_upload.*.mimes' => 'Chaque image doit être au format : jpg, jpeg, png, gif ou webp.',
            'images_upload.*.max' => 'Chaque image ne peut pas dépasser 5MB.',
            'image_alt_text.string' => 'Le texte alternatif doit être une chaîne de caractères.',
            'image_alt_text.max' => 'Le texte alternatif ne peut pas dépasser 255 caractères.',
            'slide_duration.integer' => 'La durée du slide doit être un entier.',
            'slide_duration.min' => 'La durée du slide doit être au moins 1000ms.',
            'slide_duration.max' => 'La durée du slide ne peut pas dépasser 30000ms.',
            'is_active.boolean' => 'Le statut actif doit être un booléen.',
        ];
    }
} 