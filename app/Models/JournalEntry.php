<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'description',
        'reference',
        'entry_type',
        'status',
        'total_debit',
        'total_credit',
        'created_by',
        'approved_by',
        'posted_at',
        'financial_period_id',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function financialPeriod()
    {
        return $this->belongsTo(FinancialPeriod::class, 'financial_period_id');
    }

    public function isBalanced()
    {
        return abs($this->total_debit - $this->total_credit) < 0.01;
    }

    public function post()
    {
        if (!$this->isBalanced()) {
            throw new \Exception('Journal entry is not balanced');
        }

        $this->status = 'posted';
        $this->posted_at = now();
        $this->save();

        foreach ($this->lines as $line) {
            $account = Account::find($line->account_id);
            if ($account) {
                $account->updateBalance($line->debit_amount, $line->credit_amount);
                
                // Create ledger entry
                LedgerAccount::create([
                    'account_id' => $account->id,
                    'journal_entry_line_id' => $line->id,
                    'transaction_date' => $this->entry_date,
                    'description' => $line->description ?? $this->description,
                    'debit_amount' => $line->debit_amount,
                    'credit_amount' => $line->credit_amount,
                    'balance' => $account->current_balance,
                    'reference' => $this->entry_number,
                ]);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($journalEntry) {
            if (empty($journalEntry->entry_number)) {
                $journalEntry->entry_number = 'JE-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
