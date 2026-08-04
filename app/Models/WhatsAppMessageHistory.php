<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessageHistory extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'message',
        'message_type',
        'status',
        'response',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'response' => 'array',
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSingle($query)
    {
        return $query->where('message_type', 'single');
    }

    public function scopeBulk($query)
    {
        return $query->where('message_type', 'bulk');
    }
}
