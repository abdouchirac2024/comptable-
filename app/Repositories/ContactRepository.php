<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Contact::query();
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('sujet', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                ;
            });
        }
        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?Contact
    {
        return Contact::find($id);
    }

    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact->fresh();
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }
} 