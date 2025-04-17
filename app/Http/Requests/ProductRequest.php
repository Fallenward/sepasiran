<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
			'description' => 'string',
			'slug' => 'required|string',
			'price' => 'required',
			'stock' => 'required',
			'is_active' => 'required',
			'is_featured' => 'required',
			'sku' => 'string',
			'barcode' => 'string',
			'thumbnail' => 'string',
			'buy_link' => 'string',
        ];
    }
}
