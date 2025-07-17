<?php

namespace App\Repositories\Interfaces;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Contact;
    public function create(array $data): Contact;
    public function update(Contact $contact, array $data): Contact;
    public function delete(Contact $contact): bool;
    public function search(string $term): Collection;
} 