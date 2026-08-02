<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryLine extends Model
{
    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'description',
        'debit_amount',
        'credit_amount',
        'related_member_id',
        'related_loan_id',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function relatedMember()
    {
        return $this->belongsTo(Member::class, 'related_member_id');
    }

    public function ledgerAccount()
    {
        return $this->hasOne(LedgerAccount::class);
    }
}
