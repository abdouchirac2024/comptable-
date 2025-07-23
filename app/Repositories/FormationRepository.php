<?php

namespace App\Repositories;

use App\Models\Formation;
use App\Repositories\Interfaces\FormationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FormationRepository implements FormationRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Formation::query();
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%")
                  ->orWhere('duree', 'like', "%$search%")
                  ->orWhere('tarif', 'like', "%$search%")
                ;
            });
        }
        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?Formation
    {
        return Formation::find($id);
    }

    public function create(array $data): Formation
    {
        return Formation::create($data);
    }

    public function update(Formation $formation, array $data): Formation
    {
        $formation->update($data);
        return $formation->fresh();
    }

    public function delete(Formation $formation): void
    {
        $formation->delete();
    }
} 