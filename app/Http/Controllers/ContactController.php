<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(protected ContactService $contactService) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $contacts = $this->contactService->getPaginatedContacts($perPage);
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.index_success'),
            'data' => new ContactCollection($contacts),
        ]);
    }

    public function show(Contact $contact): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.show_success'),
            'data' => new ContactResource($contact),
        ]);
    }

    public function store(CreateContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->createContact($request->validated());
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.store_success'),
            'data' => new ContactResource($contact),
        ], 201);
    }

    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        $contact = $this->contactService->updateContact($contact, $request->validated());
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.update_success'),
            'data' => new ContactResource($contact),
        ]);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $this->contactService->deleteContact($contact);
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.delete_success'),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $term = $request->get('q', '');
        $contacts = $this->contactService->searchContacts($term);
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.search_success'),
            'data' => ContactResource::collection($contacts),
        ]);
    }
} 