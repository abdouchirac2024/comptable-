<?php

namespace App\Http\Requests\ArticleBlog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $articleId = $this->route('article_blog');
        return [
            'titre' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:article_blogs,slug,' . $articleId,
            'contenu' => 'sometimes|required|string',
            'meta_titre' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'date_publication' => 'nullable|date',
        ];
    }
} 