<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Contact;

interface ContactRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Contact;
    public function create(array $data): Contact;
    public function update(Contact $contact, array $data): Contact;
    public function delete(Contact $contact): void;
} 