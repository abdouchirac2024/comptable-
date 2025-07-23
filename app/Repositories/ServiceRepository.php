<?php

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Service::query();
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%")
                  ->orWhere('categorie', 'like', "%$search%")
                  ->orWhere('tarif', 'like', "%$search%")
                  ->orWhere('duree', 'like', "%$search%")
                ;
            });
        }
        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?Service
    {
        return Service::find($id);
    }

    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($data);
        return $service;
    }

    public function delete(Service $service): void
    {
        $service->delete();
    }
} 