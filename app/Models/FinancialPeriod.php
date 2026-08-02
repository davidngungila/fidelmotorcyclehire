<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialPeriod extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'is_current',
        'closed_by',
        'closed_at',
        'closing_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'closed_at' => 'datetime',
    ];

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'financial_period_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function close()
    {
        if ($this->status === 'closed') {
            throw new \Exception('Period is already closed');
        }

        $this->status = 'closed';
        $this->is_current = false;
        $this->closed_by = auth()->id();
        $this->closed_at = now();
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($period) {
            if ($period->is_current) {
                static::where('id', '!=', $period->id)->update(['is_current' => false]);
            }
        });
    }
}
