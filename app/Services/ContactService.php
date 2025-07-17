<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    public function __construct(protected ContactRepositoryInterface $contactRepository)
    {
    }

    public function getAllContacts(): Collection
    {
        return $this->contactRepository->all();
    }

    public function getPaginatedContacts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->contactRepository->paginate($perPage);
    }

    public function getContactById(int $id): ?Contact
    {
        return $this->contactRepository->findById($id);
    }

    public function createContact(array $data): Contact
    {
        return $this->contactRepository->create($data);
    }

    public function updateContact(Contact $contact, array $data): Contact
    {
        return $this->contactRepository->update($contact, $data);
    }

    public function deleteContact(Contact $contact): bool
    {
        return $this->contactRepository->delete($contact);
    }

    public function searchContacts(string $term): Collection
    {
        return $this->contactRepository->search($term);
    }
} 