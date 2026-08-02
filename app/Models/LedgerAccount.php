<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerAccount extends Model
{
    protected $fillable = [
        'account_id',
        'journal_entry_line_id',
        'transaction_date',
        'description',
        'debit_amount',
        'credit_amount',
        'balance',
        'reference',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journalEntryLine()
    {
        return $this->belongsTo(JournalEntryLine::class);
    }
}
