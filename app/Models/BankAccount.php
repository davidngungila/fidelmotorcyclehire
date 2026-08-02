<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_name',
        'account_number',
        'bank_name',
        'branch_name',
        'account_type',
        'opening_balance',
        'current_balance',
        'related_account_id',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function relatedAccount()
    {
        return $this->belongsTo(Account::class, 'related_account_id');
    }
}
