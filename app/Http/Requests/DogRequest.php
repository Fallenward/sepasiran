<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DogRequest extends FormRequest
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
			'title' => 'string',
			'color' => 'string',
			'cover_type' => 'string',
			'eye_color' => 'string',
			'shajare' => 'required',
			'father_name' => 'string',
			'mother_name' => 'string',
			'maternal_grandmother_name' => 'string',
			'maternal_grandfather_name' => 'string',
			'paternal_grandmother_name' => 'string',
			'paternal_grandfather_name' => 'string',
        ];
    }
}
