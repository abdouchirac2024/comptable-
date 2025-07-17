<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{
    public function __construct(protected Contact $model)
    {
    }

    public function all(): Collection
    {
        return $this->getAllContacts();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->getPaginatedContacts($perPage);
    }

    public function findById(int $id): ?Contact
    {
        return $this->findContactById($id);
    }

    public function create(array $data): Contact
    {
        return $this->createContact($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        return $this->updateContact($contact, $data);
    }

    public function delete(Contact $contact): bool
    {
        return $this->deleteContact($contact);
    }

    public function search(string $term): Collection
    {
        return $this->searchContacts($term);
    }

    // Méthodes internes déjà existantes (compatibilité)
    public function getAllContacts(): Collection
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    public function getPaginatedContacts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findContactById(int $id): ?Contact
    {
        return $this->model->find($id);
    }

    public function createContact(array $data): Contact
    {
        return $this->model->create($data);
    }

    public function updateContact(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact->fresh();
    }

    public function deleteContact(Contact $contact): bool
    {
        return $contact->delete();
    }

    public function searchContacts(string $term): Collection
    {
        return $this->model
            ->where(function ($query) use ($term) {
                $query->where('nom', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('sujet', 'like', "%{$term}%")
                    ->orWhere('message', 'like', "%{$term}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
} 