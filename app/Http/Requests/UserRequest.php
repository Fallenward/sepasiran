<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userParam = $this->route('user');
        $userId = is_object($userParam) ? $userParam->id : $userParam;

        return [
            'name' => ['required', 'string', 'max:255'],
            'family' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string',
                'min:6',
                'confirmed',
            ],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string'],
            'wallet' => ['nullable', 'integer', 'min:0'],
            'is_admin' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_admin' => $this->boolean('is_admin'),
            'wallet' => $this->filled('wallet') ? (int) $this->input('wallet') : 0,
        ]);
    }
}