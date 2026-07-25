<?php

namespace App\Jobs;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Imports\MembersImport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ImportMembersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $googleSheetRepository;
    protected $userId;
    protected $jobId;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, GoogleSheetRepositoryInterface $googleSheetRepository, $userId, $jobId)
    {
        $this->filePath = $filePath;
        $this->googleSheetRepository = $googleSheetRepository;
        $this->userId = $userId;
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Cache::put("import_{$this->jobId}", [
                'status' => 'processing',
                'progress' => 0,
                'message' => 'Starting import...',
                'imported' => 0,
                'total' => 0,
            ], 3600);

            $import = new MembersImport($this->googleSheetRepository);
            
            Excel::import($import, $this->filePath);

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            $createdUsers = $import->getCreatedUsers();

            Cache::put("import_{$this->jobId}", [
                'status' => 'completed',
                'progress' => 100,
                'message' => 'Import completed successfully',
                'imported' => $importedCount,
                'created_users' => count($createdUsers),
                'errors' => $errors,
                'total' => $importedCount + count($errors),
            ], 3600);

            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => $this->userId,
                'description' => 'Admin imported members from Excel',
                'properties' => [
                    'imported_count' => $importedCount,
                    'created_users_count' => count($createdUsers),
                    'created_users' => $createdUsers,
                    'errors_count' => count($errors),
                    'errors' => $errors,
                ],
            ]);

            // Clean up file
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }
        } catch (\Exception $e) {
            Cache::put("import_{$this->jobId}", [
                'status' => 'failed',
                'progress' => 0,
                'message' => 'Import failed: ' . $e->getMessage(),
                'imported' => 0,
                'total' => 0,
                'error' => $e->getMessage(),
            ], 3600);

            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }
        }
    }
}
