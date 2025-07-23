<?php

namespace App\Services;

use App\Repositories\Interfaces\ArticleBlogRepositoryInterface;
use App\Models\ArticleBlog;

class ArticleBlogService
{
    protected $articleBlogRepository;

    public function __construct(ArticleBlogRepositoryInterface $articleBlogRepository)
    {
        $this->articleBlogRepository = $articleBlogRepository;
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->articleBlogRepository->all($filters, $perPage);
    }

    public function find(int $id): ?ArticleBlog
    {
        return $this->articleBlogRepository->find($id);
    }

    public function create(array $data): ArticleBlog
    {
        if (isset($data['image']) && is_file($data['image'])) {
            $data['image'] = $data['image']->store('article-blogs', 'public');
        }
        return $this->articleBlogRepository->create($data);
    }

    public function update(ArticleBlog $article, array $data): ArticleBlog
    {
        if (isset($data['image']) && is_file($data['image'])) {
            // Supprimer l'ancienne image si elle existe
            if ($article->image) {
                \Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $data['image']->store('article-blogs', 'public');
        }
        return $this->articleBlogRepository->update($article, $data);
    }

    public function delete(ArticleBlog $article): void
    {
        $this->articleBlogRepository->delete($article);
    }
} 