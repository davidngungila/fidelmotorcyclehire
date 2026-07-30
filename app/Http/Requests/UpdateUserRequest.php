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
                \Log::info('UpdateUserRequest ID decryption', [
                    'encrypted_id' => $userId,
                    'decrypted_id' => $decryptedId,
                    'is_numeric' => is_numeric($decryptedId)
                ]);
                if ($decryptedId && is_numeric($decryptedId)) {
                    $userId = (int) $decryptedId;
                } else {
                    \Log::warning('Decrypted ID is not numeric or null', ['decrypted_id' => $decryptedId]);
                    $userId = null;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to decrypt user ID in UpdateUserRequest', [
                    'encrypted_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                $userId = null;
            }
        } else {
            \Log::warning('No encrypted ID found in route');
        }
        
        \Log::info('UpdateUserRequest validation rules', ['user_id' => $userId]);
        
        return [
            // Basic Information
            'member_type_id' => ['nullable', 'exists:member_types,id'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'passport_license' => ['nullable', 'string', 'max:50'],
            'member_number' => ['nullable', Rule::unique('users', 'member_number')->ignore($userId ?? 0)],
            'registration_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,pending,suspended,inactive'],
            
            // Contact Information
            'phone' => ['nullable', 'string', 'max:20'],
            'alternative_phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId ?? 0)],
            'region' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'ward' => ['nullable', 'string', 'max:100'],
            'street_village' => ['nullable', 'string', 'max:100'],
            'physical_address' => ['nullable', 'string'],
            
            // Membership Details
            'branch' => ['nullable', 'string', 'max:100'],
            'membership_category' => ['nullable', 'string', 'max:100'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'employer_business' => ['nullable', 'string', 'max:100'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'introduced_by' => ['nullable', 'string', 'max:100'],
            'joining_fee' => ['nullable', 'numeric', 'min:0'],
            'shares_purchased' => ['nullable', 'numeric', 'min:0'],
            'initial_savings' => ['nullable', 'numeric', 'min:0'],
            
            // Account Information
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users', 'username')->ignore($userId ?? 0)],
            'role' => ['required', 'in:admin,manager,teller,member,auditor'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'email_verified' => ['nullable', 'boolean'],
            'phone_verified' => ['nullable', 'boolean'],
            
            // Next of Kin
            'next_of_kin_full_name' => ['nullable', 'string', 'max:100'],
            'next_of_kin_relationship' => ['nullable', 'string', 'max:50'],
            'next_of_kin_phone' => ['nullable', 'string', 'max:20'],
            'next_of_kin_address' => ['nullable', 'string'],
            
            // Banking Details
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'mobile_money_network' => ['nullable', 'in:m-pesa,tigopesa,airtel,halopesa'],
            'mobile_wallet_number' => ['nullable', 'string', 'max:20'],
            
            // Documents
            'passport_photo' => ['nullable', 'string', 'max:255'],
            'national_id_copy' => ['nullable', 'string', 'max:255'],
            'signature' => ['nullable', 'string', 'max:255'],
            'other_attachments' => ['nullable', 'string', 'max:255'],
            
            // Additional Information
            'notes' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            'custom_fields' => ['nullable', 'array'],
        ];
    }
}
