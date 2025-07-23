<?php

namespace App\Repositories;

use App\Models\ArticleBlog;
use App\Repositories\Interfaces\ArticleBlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleBlogRepository implements ArticleBlogRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = ArticleBlog::query();
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                  ->orWhere('contenu', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%")
                  ->orWhere('meta_titre', 'like', "%$search%")
                  ->orWhere('meta_description', 'like', "%$search%")
                ;
            });
        }
        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?ArticleBlog
    {
        return ArticleBlog::find($id);
    }

    public function create(array $data): ArticleBlog
    {
        return ArticleBlog::create($data);
    }

    public function update(ArticleBlog $article, array $data): ArticleBlog
    {
        $article->update($data);
        return $article;
    }

    public function delete(ArticleBlog $article): void
    {
        $article->delete();
    }
} 