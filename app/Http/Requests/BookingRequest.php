<?php

namespace App\Http\Requests;

use App\Services\BookingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'service_type' => ['required', Rule::in(array_keys(BookingService::SERVICE_TYPES))],
            'provider_id' => ['required', 'integer', 'exists:users,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'breed_id' => ['nullable', 'integer', 'exists:breeds,id'],
            'training_type' => ['nullable', Rule::in(array_keys(BookingService::TRAINING_TYPES))],
            'appointment_date' => ['required', 'date'],
            'slot' => ['nullable', Rule::in(BookingService::SLOT_TEMPLATE)],
            'status' => ['required', Rule::in(['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])],
            'notes' => ['nullable', 'string'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'payment_method' => ['nullable', Rule::in(['online', 'offline'])],
            'payment_status' => ['nullable', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
            'price' => ['nullable', 'numeric'],
            'tax' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
        ];
    }
}
