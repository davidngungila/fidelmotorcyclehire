<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessageHistory extends Model
{
    protected $table = 'whatsapp_message_history';

    protected $fillable = [
        'user_id',
        'phone_number',
        'message',
        'message_type',
        'media_type',
        'media_data',
        'status',
        'response',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'response'   => 'array',
        'media_data' => 'array',
        'sent_at'    => 'datetime',
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

    public function scopeByMediaType($query, string $type)
    {
        return $query->where('media_type', $type);
    }

    public function scopeText($query)
    {
        return $query->where('media_type', 'text');
    }

    public function scopeImage($query)
    {
        return $query->where('media_type', 'image');
    }

    public function scopeVideo($query)
    {
        return $query->where('media_type', 'video');
    }

    public function scopeDocument($query)
    {
        return $query->where('media_type', 'document');
    }

    public function scopeAudio($query)
    {
        return $query->where('media_type', 'audio');
    }

    public function scopeSticker($query)
    {
        return $query->where('media_type', 'sticker');
    }

    public function scopeContact($query)
    {
        return $query->where('media_type', 'contact');
    }

    public function scopeLocation($query)
    {
        return $query->where('media_type', 'location');
    }

    public function isText(): bool
    {
        return $this->media_type === 'text';
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    public function isDocument(): bool
    {
        return $this->media_type === 'document';
    }

    public function isAudio(): bool
    {
        return $this->media_type === 'audio';
    }

    public function isSticker(): bool
    {
        return $this->media_type === 'sticker';
    }

    public function isContact(): bool
    {
        return $this->media_type === 'contact';
    }

    public function isLocation(): bool
    {
        return $this->media_type === 'location';
    }

    public function getMediaTypeLabelAttribute(): string
    {
        return match ($this->media_type) {
            'text'     => 'Text',
            'image'    => 'Image',
            'video'    => 'Video',
            'document' => 'Document',
            'audio'    => 'Audio',
            'sticker'  => 'Sticker',
            'contact'  => 'Contact',
            'location' => 'Location',
            default    => ucfirst($this->media_type ?? 'unknown'),
        };
    }
}
