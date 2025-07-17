<?php

namespace App\Repositories\Interfaces;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategorieRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Categorie;
    public function findBySlug(string $slug): ?Categorie;
    public function create(array $data): Categorie;
    public function update(Categorie $categorie, array $data): Categorie;
    public function delete(Categorie $categorie): bool;
    public function search(string $term): Collection;
} 