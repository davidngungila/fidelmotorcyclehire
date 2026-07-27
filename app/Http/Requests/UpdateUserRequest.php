<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                $decryptedId = app(\App\Services\EncryptedIdService::class)->decrypt($userId);
                if ($decryptedId && is_numeric($decryptedId)) {
                    $userId = (int) $decryptedId;
                } else {
                    $userId = null;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to decrypt user ID in UpdateUserRequest', [
                    'encrypted_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                $userId = null;
            }
        }
        
        return [
            'name' => ['required'],
            'email' => ['required', Rule::unique('users', 'email')->ignore($userId ?? 0)],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'role' => ['required'],
            'member_number' => ['nullable', Rule::unique('users', 'member_number')->ignore($userId ?? 0)],
        ];
    }
}
