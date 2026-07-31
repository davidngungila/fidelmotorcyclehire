<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_token',
        'sender_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
