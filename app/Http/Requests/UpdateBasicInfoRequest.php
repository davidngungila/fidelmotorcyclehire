<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Services\EncryptedIdService;

class UpdateBasicInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $encryptedId = $this->route('encryptedId');
        $userId = null;
        
        if ($encryptedId) {
            try {
                $userId = (int) app(EncryptedIdService::class)->decrypt($encryptedId);
            } catch (\Exception $e) {
                // If decryption fails, we'll handle it in the controller
            }
        }
        
        $profileId = null;
        if ($userId) {
            $profile = \App\Models\MemberProfile::where('user_id', $userId)->first();
            if ($profile) {
                $profileId = $profile->id;
            }
        }

        return [
            'member_type_id' => 'nullable|exists:member_types,id',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255|unique:users,email,' . $userId,
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'national_id' => 'nullable|string|max:50|unique:member_profiles,national_id,' . $profileId,
            'passport_driving_license' => 'nullable|string|max:50',
            'registration_date' => 'nullable|date',
            'status' => 'nullable|in:active,pending,suspended',
            'profile_photo' => 'nullable|image|max:5120',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
