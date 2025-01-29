<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
        $rules = [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'booking_date' => 'required|date',
            'notes' => 'nullable|string',
        ];

        // Only apply service_provider_id validation rule on CREATE (POST method)
        if ($this->isMethod('post')) {
            $rules['service_provider_id'] = 'required|exists:users,id';
        }

        return $rules;
    }
}
