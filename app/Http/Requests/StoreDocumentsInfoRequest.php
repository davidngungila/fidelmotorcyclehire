<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentsInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passport_photo' => 'nullable|image|max:5120',
            'national_id_copy' => 'nullable|file|max:10240',
            'signature' => 'nullable|image|max:2048',
            'other_attachments' => 'nullable|array',
            'other_attachments.*' => 'file|max:10240',
        ];
    }
}
