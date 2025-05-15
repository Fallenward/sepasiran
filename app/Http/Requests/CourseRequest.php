<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
			'title' => 'required|string',
			'slug' => 'required|string',
			'short_description' => 'string',
			'description' => 'string',
			'thumbnail' => 'string',
			'instructor_id' => 'required',
			'price' => 'required',
			'status' => 'required|string',
			'is_featured' => 'required',
			'has_certificate' => 'required',
			'language' => 'required|string',
			'video_demo_url' => 'string',
			'view_count' => 'required',
			'is_active' => 'required',
        ];
    }
}
