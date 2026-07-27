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
        $userId = $this->route('encryptedId');
        
        // Decrypt the ID if it's encrypted
        if ($userId) {
            try {
                $userId = app(\App\Services\EncryptedIdService::class)->decrypt($userId);
            } catch (\Exception $e) {
                $userId = null;
            }
        }
        
        return [
            'name' => ['required'],
            'email' => ['required', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'role' => ['required'],
            'member_number' => ['nullable', 'unique:users,member_number,' . $userId],
        ];
    }
}
