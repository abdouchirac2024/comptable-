<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Partenaire;

interface PartenaireRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Partenaire;
    public function create(array $data): Partenaire;
    public function update(Partenaire $partenaire, array $data): Partenaire;
    public function delete(Partenaire $partenaire): void;
} 