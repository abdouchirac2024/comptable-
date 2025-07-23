<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Mission;

interface MissionRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Mission;
    public function create(array $data): Mission;
    public function update(Mission $mission, array $data): Mission;
    public function delete(Mission $mission): void;
} 