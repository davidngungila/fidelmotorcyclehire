<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'membership_category' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer_business' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'introduced_by' => 'nullable|string|max:255',
            'joining_fee' => 'required|numeric|min:0',
            'shares_purchased' => 'required|numeric|min:0',
            'initial_savings_deposit' => 'required|numeric|min:0',
        ];
    }
}
