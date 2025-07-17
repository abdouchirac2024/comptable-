<?php

return [
    'messages' => [
        'index_success' => 'Categories list retrieved successfully',
        'index_error' => 'Error retrieving categories',
        'all_success' => 'All categories retrieved successfully',
        'all_error' => 'Error retrieving all categories',
        'show_success' => 'Category retrieved successfully',
        'show_error' => 'Error retrieving category',
        'store_success' => 'Category created successfully',
        'store_error' => 'Error creating category',
        'update_success' => 'Category updated successfully',
        'update_error' => 'Error updating category',
        'delete_success' => 'Category deleted successfully',
        'delete_error' => 'Error deleting category',
        'not_found' => 'Category not found',
        'search_success' => 'Category search completed successfully',
        'search_error' => 'Error searching categories',
        'search_term_required' => 'Search term is required',
    ],
    'validation' => [
        'failed' => 'Validation error',
        'nom' => [
            'required' => 'Category name is required',
            'string' => 'Name must be a string',
            'max' => 'Name cannot exceed 255 characters',
            'unique' => 'This category name already exists',
        ],
        'description' => [
            'string' => 'Description must be a string',
            'max' => 'Description cannot exceed 1000 characters',
        ],
        'slug' => [
            'string' => 'Slug must be a string',
            'max' => 'Slug cannot exceed 255 characters',
            'unique' => 'This slug already exists',
        ],
    ],
]; 