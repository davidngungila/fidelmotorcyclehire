<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'unique:users,email,' . $this->id],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'role' => ['required'],
        ];
    }
}
