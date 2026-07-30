<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone_number' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'region' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'street_village' => 'nullable|string|max:255',
            'physical_address' => 'nullable|string',
        ];
    }
}
