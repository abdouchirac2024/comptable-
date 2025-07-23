<?php

namespace App\Services;

use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getPaginatedContacts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->contactRepository->all([], $perPage);
    }

    public function find(int $id): ?Contact
    {
        return $this->contactRepository->find($id);
    }

    public function createContact(array $data): Contact
    {
        return $this->contactRepository->create($data);
    }

    public function updateContact(Contact $contact, array $data): Contact
    {
        return $this->contactRepository->update($contact, $data);
    }

    public function deleteContact(Contact $contact): void
    {
        $this->contactRepository->delete($contact);
    }

    public function searchContacts(string $term)
    {
        return $this->contactRepository->all(['search' => $term], 15);
    }
} 