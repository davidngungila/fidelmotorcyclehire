<?php

namespace App\Console\Commands;

use App\Models\SyncLog;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanSyncLogs extends Command
{
    protected $signature = 'sync:clean-logs {--days=30 : Number of days to keep}';
    protected $description = 'Clean old sync logs';

    public function handle()
    {
        $days = $this->option('days');
        $cutoff = Carbon::now()->subDays($days);
        
        $deleted = SyncLog::where('created_at', '<', $cutoff)->delete();
        
        $this->info("Deleted {$deleted} old sync logs.");
        return 0;
    }
}
