<?php

namespace App\Repositories;

use App\Models\Mission;
use App\Repositories\Interfaces\MissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MissionRepository implements MissionRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Mission::query();
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%")
                ;
            });
        }
        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?Mission
    {
        return Mission::find($id);
    }

    public function create(array $data): Mission
    {
        return Mission::create($data);
    }

    public function update(Mission $mission, array $data): Mission
    {
        $mission->update($data);
        return $mission;
    }

    public function delete(Mission $mission): void
    {
        $mission->delete();
    }
} 