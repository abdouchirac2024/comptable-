<?php

namespace App\Http\Requests\ArticleBlog;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'meta_titre' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:article_blogs,slug',
            'date_publication' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
} 