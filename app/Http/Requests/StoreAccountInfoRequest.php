<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'nullable|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,member',
            'email_verified' => 'boolean',
            'phone_verified' => 'boolean',
        ];
    }
}
