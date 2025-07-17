<?php

return [
    'messages' => [
        'index_success' => 'Liste des catégories récupérée avec succès',
        'index_error' => 'Erreur lors de la récupération des catégories',
        'all_success' => 'Toutes les catégories récupérées avec succès',
        'all_error' => 'Erreur lors de la récupération de toutes les catégories',
        'show_success' => 'Catégorie récupérée avec succès',
        'show_error' => 'Erreur lors de la récupération de la catégorie',
        'store_success' => 'Catégorie créée avec succès',
        'store_error' => 'Erreur lors de la création de la catégorie',
        'update_success' => 'Catégorie mise à jour avec succès',
        'update_error' => 'Erreur lors de la mise à jour de la catégorie',
        'delete_success' => 'Catégorie supprimée avec succès',
        'delete_error' => 'Erreur lors de la suppression de la catégorie',
        'not_found' => 'Catégorie non trouvée',
        'search_success' => 'Recherche de catégories effectuée avec succès',
        'search_error' => 'Erreur lors de la recherche de catégories',
        'search_term_required' => 'Le terme de recherche est requis',
    ],
    'validation' => [
        'failed' => 'Erreur de validation',
        'nom' => [
            'required' => 'Le nom de la catégorie est requis',
            'string' => 'Le nom doit être une chaîne de caractères',
            'max' => 'Le nom ne peut pas dépasser 255 caractères',
            'unique' => 'Ce nom de catégorie existe déjà',
        ],
        'description' => [
            'string' => 'La description doit être une chaîne de caractères',
            'max' => 'La description ne peut pas dépasser 1000 caractères',
        ],
        'slug' => [
            'string' => 'Le slug doit être une chaîne de caractères',
            'max' => 'Le slug ne peut pas dépasser 255 caractères',
            'unique' => 'Ce slug existe déjà',
        ],
    ],
]; 