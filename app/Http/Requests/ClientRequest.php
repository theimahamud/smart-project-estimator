<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                // Note: Email uniqueness validation could be enhanced in the future
                // Rule::unique('clients')->where(fn ($query) => $query->where('user_id', auth()->id()))->ignore($clientId)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Client name is required.',
            'name.max' => 'Client name cannot exceed 255 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'You already have a client with this email address.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'company.max' => 'Company name cannot exceed 255 characters.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'client name',
            'email' => 'email address',
            'phone' => 'phone number',
            'company' => 'company name',
            'address' => 'address',
            'notes' => 'notes',
        ];
    }
}
