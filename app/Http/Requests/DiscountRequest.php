<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
			'code' => 'required|string',
			'course_ids' => 'string',
			'user_ids' => 'string',
			'percentage' => 'required',
			'use_count' => 'required',
			'status' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
        ];
    }
}
