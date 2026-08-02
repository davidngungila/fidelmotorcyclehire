<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanCompletionCertificate extends Model
{
    protected $fillable = [
        'certificate_number',
        'loan_id',
        'completion_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
