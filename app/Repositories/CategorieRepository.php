<?php

namespace App\Repositories;

use App\Models\Categorie;
use App\Repositories\Interfaces\CategorieRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategorieRepository implements CategorieRepositoryInterface
{
    public function __construct(protected Categorie $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->orderBy('nom')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('nom')->paginate($perPage);
    }

    public function findById(int $id): ?Categorie
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?Categorie
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Categorie
    {
        // Générer le slug automatiquement si non fourni
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nom']);
        }

        return $this->model->create($data);
    }

    public function update(Categorie $categorie, array $data): Categorie
    {
        // Générer le slug automatiquement si le nom change
        if (isset($data['nom']) && $data['nom'] !== $categorie->nom) {
            $data['slug'] = Str::slug($data['nom']);
        }

        $categorie->update($data);
        return $categorie->fresh();
    }

    public function delete(Categorie $categorie): bool
    {
        return $categorie->delete();
    }

    public function search(string $term): Collection
    {
        return $this->model
            ->where('nom', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
            ->orderBy('nom')
            ->get();
    }
} 