<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBasicInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_type_id' => 'required|exists:member_types,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'national_id' => 'nullable|string|max:50|unique:member_profiles,national_id',
            'passport_driving_license' => 'nullable|string|max:50',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,pending,suspended',
        ];
    }
}
