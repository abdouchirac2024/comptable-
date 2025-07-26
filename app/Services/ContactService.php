<?php

namespace App\Services;

use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Mail\ContactAutoReply;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReply;

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
        $contact = $this->contactRepository->create($data);
        return $contact;
    }

    public function updateContact(Contact $contact, array $data): Contact
    {
        $contact = $this->contactRepository->update($contact, $data);
        
        // Envoi d'email uniquement si une réponse est fournie
        if (!empty($data['reponse']) && !empty($contact->email)) {
            // Envoi asynchrone pour plus de fluidité
            Mail::to($contact->email)->queue(new ContactReply($contact->nom, $data['reponse']));
        }
        
        return $contact;
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