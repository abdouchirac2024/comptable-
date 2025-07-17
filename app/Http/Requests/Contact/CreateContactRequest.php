<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class CreateContactRequest extends FormRequest
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
            'message' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => __('contacts.validation.nom.required'),
            'email.required' => __('contacts.validation.email.required'),
            'email.email' => __('contacts.validation.email.email'),
            'message.required' => __('contacts.validation.message.required'),
        ];
    }
} 