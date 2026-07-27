<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $table = 'sync_logs';

    protected $fillable = [
        'sync_type', 'started_at', 'completed_at',
        'status', 'records_synced', 'records_failed',
        'summary', 'error'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'summary' => 'array'
    ];

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeLastSixHours($query)
    {
        return $query->where('created_at', '>=', now()->subHours(6));
    }

    public function scopeByType($query, $type)
    {
        return $query->where('sync_type', $type);
    }

    public function getDurationAttribute()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->started_at->diffInSeconds($this->completed_at);
        }
        return null;
    }

    public function getFormattedDurationAttribute()
    {
        $duration = $this->duration;
        if (!$duration) return 'In progress';
        
        $minutes = floor($duration / 60);
        $seconds = $duration % 60;
        return "{$minutes}m {$seconds}s";
    }
}
