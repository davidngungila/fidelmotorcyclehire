<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankingInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'mobile_money_network' => 'nullable|string|max:50',
            'mobile_wallet_number' => 'nullable|string|max:20',
        ];
    }
}
