<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsSyncService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SyncGoogleSheets extends Command
{
    protected $signature = 'sync:google-sheets
                            {--all : Sync all data}
                            {--active : Sync only active customers}
                            {--force : Force sync even if already synced}
                            {--type= : Sync specific type (customers|transactions|balances)}';

    protected $description = 'Sync data from Google Sheets to Laravel';

    protected $syncService;

    public function __construct(GoogleSheetsSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    public function handle()
    {
        $this->info('🔄 Starting Google Sheets sync at ' . Carbon::now()->toDateTimeString());

        try {
            $type = 'all';
            if ($this->option('active')) {
                $type = 'active';
            } elseif ($this->option('type')) {
                $type = $this->option('type');
            }

            $force = $this->option('force');

            $result = $this->syncService->triggerSync($type, $force);

            if ($result['success']) {
                $this->info('✅ Sync completed successfully!');
                $this->info('📊 Records synced: ' . ($result['records_synced'] ?? 0));
                $this->info('📊 Records failed: ' . ($result['records_failed'] ?? 0));
            } else {
                $this->error('❌ Sync failed: ' . ($result['error'] ?? 'Unknown error'));
                return 1;
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Sync failed: ' . $e->getMessage());
            return 1;
        }
    }
}
