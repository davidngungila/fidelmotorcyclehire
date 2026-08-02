<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCompletionCertificate extends Model
{
    protected $fillable = [
        'loan_id',
        'user_id',
        'certificate_number',
        'completion_date',
        'original_amount',
        'total_paid',
        'total_interest_paid',
        'issue_date',
        'issued_by',
        'signature',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'total_interest_paid' => 'decimal:2',
        'completion_date' => 'date',
        'issue_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generateCertificateNumber()
    {
        $prefix = 'LCC';
        $date = now()->format('Ymd');
        $lastCertificate = self::where('certificate_number', 'like', $prefix . $date . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastCertificate 
            ? (int) substr($lastCertificate->certificate_number, -4) + 1 
            : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
