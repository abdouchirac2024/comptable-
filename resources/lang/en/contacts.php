<?php

return [
    'messages' => [
        'index_success' => 'Contacts list retrieved successfully',
        'show_success' => 'Contact retrieved successfully',
        'store_success' => 'Contact created successfully',
        'update_success' => 'Contact updated successfully',
        'delete_success' => 'Contact deleted successfully',
        'search_success' => 'Contact search completed successfully',
    ],
    'validation' => [
        'nom' => [
            'required' => 'The name field is required.',
        ],
        'email' => [
            'required' => 'The email field is required.',
            'email' => 'The email must be a valid email address.',
        ],
        'message' => [
            'required' => 'The message field is required.',
        ],
    ],
]; 