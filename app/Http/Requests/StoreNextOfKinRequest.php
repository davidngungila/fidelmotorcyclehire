<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNextOfKinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kin_full_name' => 'nullable|string|max:255',
            'kin_relationship' => 'nullable|string|max:255',
            'kin_phone_number' => 'nullable|string|max:20',
            'kin_address' => 'nullable|string',
        ];
    }
}
