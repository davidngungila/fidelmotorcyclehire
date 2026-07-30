<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Basic Information
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'national_id' => ['nullable', 'string', 'max:255'],
            'passport_license' => ['nullable', 'string', 'max:255'],
            'member_number' => ['nullable', 'unique:users'],
            'registration_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,pending,suspended'],
            'member_type_id' => ['nullable', 'exists:member_types,id'],
            
            // Contact Information
            'phone' => ['nullable', 'string', 'max:255'],
            'alternative_phone' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'region' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'ward' => ['nullable', 'string', 'max:255'],
            'street_village' => ['nullable', 'string', 'max:255'],
            'physical_address' => ['nullable', 'string'],
            
            // Membership Details
            'branch' => ['nullable', 'string', 'max:255'],
            'membership_category' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'employer_business' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'introduced_by' => ['nullable', 'string', 'max:255'],
            'joining_fee' => ['nullable', 'numeric', 'min:0'],
            'shares_purchased' => ['nullable', 'numeric', 'min:0'],
            'initial_savings' => ['nullable', 'numeric', 'min:0'],
            
            // Account Information
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'in:admin,member'],
            'email_verified' => ['nullable', 'boolean'],
            'phone_verified' => ['nullable', 'boolean'],
            
            // Next of Kin
            'next_of_kin_full_name' => ['nullable', 'string', 'max:255'],
            'next_of_kin_relationship' => ['nullable', 'string', 'max:255'],
            'next_of_kin_phone' => ['nullable', 'string', 'max:255'],
            'next_of_kin_address' => ['nullable', 'string'],
            
            // Banking & Mobile Money
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'mobile_money_network' => ['nullable', 'string', 'max:255'],
            'mobile_wallet_number' => ['nullable', 'string', 'max:255'],
            
            // Documents
            'passport_photo' => ['nullable', 'string', 'max:255'],
            'national_id_copy' => ['nullable', 'string', 'max:255'],
            'signature' => ['nullable', 'string', 'max:255'],
            'other_attachments' => ['nullable', 'string', 'max:255'],
            
            // Additional Information
            'notes' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'custom_fields' => ['nullable', 'array'],
        ];
    }
}
