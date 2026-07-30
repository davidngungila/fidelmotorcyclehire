<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'national_id',
        'passport_driving_license',
        'registration_date',
        'status',
        'phone_number',
        'alternative_phone',
        'email_address',
        'region',
        'district',
        'ward',
        'street_village',
        'physical_address',
        'branch_id',
        'membership_category',
        'occupation',
        'employer_business',
        'monthly_income',
        'introduced_by',
        'joining_fee',
        'shares_purchased',
        'initial_savings_deposit',
        'kin_full_name',
        'kin_relationship',
        'kin_phone_number',
        'kin_address',
        'bank_name',
        'bank_account_number',
        'account_name',
        'mobile_money_network',
        'mobile_wallet_number',
        'passport_photo',
        'national_id_copy',
        'signature',
        'other_attachments',
        'notes',
        'tags',
        'custom_fields',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'date',
        'monthly_income' => 'decimal:2',
        'joining_fee' => 'decimal:2',
        'shares_purchased' => 'decimal:2',
        'initial_savings_deposit' => 'decimal:2',
        'other_attachments' => 'array',
        'tags' => 'array',
        'custom_fields' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ?? '') . ' ' . $this->last_name);
    }
}
