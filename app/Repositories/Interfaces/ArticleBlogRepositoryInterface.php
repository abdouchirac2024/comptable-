<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\ArticleBlog;

interface ArticleBlogRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?ArticleBlog;
    public function create(array $data): ArticleBlog;
    public function update(ArticleBlog $articleBlog, array $data): ArticleBlog;
    public function delete(ArticleBlog $articleBlog): void;
} 