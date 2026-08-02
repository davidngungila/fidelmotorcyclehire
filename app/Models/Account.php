<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'account_subtype',
        'description',
        'opening_balance',
        'current_balance',
        'is_active',
        'is_system_account',
        'parent_account_id',
        'level',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_system_account' => 'boolean',
    ];

    public function parentAccount()
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    public function childAccounts()
    {
        return $this->hasMany(Account::class, 'parent_account_id');
    }

    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function ledgerAccounts()
    {
        return $this->hasMany(LedgerAccount::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'related_account_id');
    }

    public function fixedAssets()
    {
        return $this->hasMany(FixedAsset::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function isDebitAccount()
    {
        return in_array($this->account_type, ['asset', 'expense']);
    }

    public function isCreditAccount()
    {
        return in_array($this->account_type, ['liability', 'equity', 'revenue']);
    }

    public function updateBalance($debitAmount, $creditAmount)
    {
        if ($this->isDebitAccount()) {
            $this->current_balance += ($debitAmount - $creditAmount);
        } else {
            $this->current_balance += ($creditAmount - $debitAmount);
        }
        $this->save();
    }
}
