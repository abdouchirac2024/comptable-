<?php

namespace App\Repositories;

use App\Models\Partenaire;
use App\Repositories\Interfaces\PartenaireRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PartenaireRepository implements PartenaireRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Partenaire::query();
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                ;
            });
        }
        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?Partenaire
    {
        return Partenaire::find($id);
    }

    public function create(array $data): Partenaire
    {
        return Partenaire::create($data);
    }

    public function update(Partenaire $partenaire, array $data): Partenaire
    {
        $partenaire->update($data);
        return $partenaire;
    }

    public function delete(Partenaire $partenaire): void
    {
        $partenaire->delete();
    }
} 