<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Service;

interface ServiceRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Service;
    public function create(array $data): Service;
    public function update(Service $service, array $data): Service;
    public function delete(Service $service): void;
} 