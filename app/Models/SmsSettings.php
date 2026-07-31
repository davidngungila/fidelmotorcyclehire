<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'api_key',
        'api_secret',
        'sender_id',
        'phone_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
