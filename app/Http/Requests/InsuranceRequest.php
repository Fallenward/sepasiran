<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsuranceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'name' => 'required|string',
			'slug' => 'required|string',
			'description' => 'string',
			'logo' => 'string',
			'contact_phone' => 'string',
			'contact_email' => 'string',
			'website' => 'string',
			'is_active' => 'required',
			'is_featured' => 'required',
        ];
    }
}
