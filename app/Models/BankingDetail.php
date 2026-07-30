<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_account_number',
        'account_name',
        'mobile_money_network',
        'mobile_wallet_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
