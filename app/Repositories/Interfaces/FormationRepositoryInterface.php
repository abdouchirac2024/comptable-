<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Formation;

interface FormationRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Formation;
    public function create(array $data): Formation;
    public function update(Formation $formation, array $data): Formation;
    public function delete(Formation $formation): void;
} 