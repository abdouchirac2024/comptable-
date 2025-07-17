<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'nullable|string|max:255',
            'message' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('contacts.validation.nom.required'),
            'nom.string' => __('contacts.validation.nom.string'),
            'nom.max' => __('contacts.validation.nom.max'),
            'email.required' => __('contacts.validation.email.required'),
            'email.email' => __('contacts.validation.email.email'),
            'email.max' => __('contacts.validation.email.max'),
            'sujet.string' => __('contacts.validation.sujet.string'),
            'sujet.max' => __('contacts.validation.sujet.max'),
            'message.required' => __('contacts.validation.message.required'),
            'message.string' => __('contacts.validation.message.string'),
        ];
    }
} 