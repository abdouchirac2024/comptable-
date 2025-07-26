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
        $lang = $request->header('Accept-Language', 'fr');
        $contacts = $this->contactService->getPaginatedContacts($perPage);
        $data = $contacts->getCollection()->map(function ($contact) use ($lang) {
            return [
                'id' => $contact->id,
                'nom' => $contact->nom,
                'email' => $contact->email,
                'sujet' => $lang === 'en' ? $contact->sujet_en : $contact->sujet,
                'sujet_en' => $contact->sujet_en,
                'message' => $lang === 'en' ? $contact->message_en : $contact->message,
                'message_en' => $contact->message_en,
                'created_at' => $contact->created_at,
                'updated_at' => $contact->updated_at,
            ];
        });
        $paginated = $contacts->toArray();
        $paginated['data'] = $data;
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.index_success'),
            'data' => $paginated,
        ]);
    }

    public function show(Request $request, Contact $contact): JsonResponse
    {
        $lang = $request->header('Accept-Language', 'fr');
        return response()->json([
            'success' => true,
            'message' => __('contacts.messages.show_success'),
            'data' => [
                'id' => $contact->id,
                'nom' => $contact->nom,
                'email' => $contact->email,
                'sujet' => $lang === 'en' ? $contact->sujet_en : $contact->sujet,
                'sujet_en' => $contact->sujet_en,
                'message' => $lang === 'en' ? $contact->message_en : $contact->message,
                'message_en' => $contact->message_en,
                'created_at' => $contact->created_at,
                'updated_at' => $contact->updated_at,
            ],
        ]);
    }

    public function store(CreateContactRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        $contact = $this->contactService->createContact($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'data' => [
                'id' => $contact->id,
                'nom' => $contact->nom,
                'email' => $contact->email,
                'sujet' => $contact->sujet,
                'message' => $contact->message,
                'created_at' => $contact->created_at
            ]
        ], 201);
    }

    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        $data = $request->validated();
        $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en');
        $translator->setSource('fr');
        if (!empty($data['sujet'])) {
            $data['sujet_en'] = $translator->translate($data['sujet']);
        }
        if (!empty($data['message'])) {
            $data['message_en'] = $translator->translate($data['message']);
        }
        $contact = $this->contactService->updateContact($contact, $data);
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
