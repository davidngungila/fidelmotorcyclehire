<?php

namespace App\Services;

use App\Models\CustomerProfile;
use App\Models\SavingBalance;
use App\Models\SavingPlan;
use App\Models\Transaction;
use App\Models\SyncLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoogleSheetsSyncService
{
    protected $apiUrl;
    protected $batchSize = 100;

    public function __construct()
    {
        $this->apiUrl = config('services.google_sheets.api_url');
        $this->batchSize = config('services.google_sheets.batch_size', 100);
    }

    /**
     * Trigger sync from Google Sheets
     */
    public function triggerSync($type = 'all', $force = false)
    {
        $startTime = Carbon::now();
        $syncLog = SyncLog::create([
            'sync_type' => $type . '_sync',
            'started_at' => $startTime,
            'status' => 'running'
        ]);

        try {
            $response = $this->callGoogleSheetsApi('sync-data', [
                'type' => $type,
                'force' => $force
            ]);

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Sync failed');
            }

            $this->processSyncedData($response['data'] ?? []);

            $syncLog->update([
                'completed_at' => Carbon::now(),
                'status' => 'completed',
                'records_synced' => $response['total'] ?? 0,
                'records_failed' => $response['failed'] ?? 0,
                'summary' => $response['summary'] ?? []
            ]);

            return [
                'success' => true,
                'log_id' => $syncLog->id,
                'message' => 'Sync completed successfully'
            ];

        } catch (\Exception $e) {
            $syncLog->update([
                'completed_at' => Carbon::now(),
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);

            Log::error('Google Sheets sync failed: ' . $e->getMessage());

            return [
                'success' => false,
                'log_id' => $syncLog->id,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process synced data
     */
    protected function processSyncedData($data)
    {
        DB::beginTransaction();

        try {
            // Process customers
            if (isset($data['customers'])) {
                foreach ($data['customers'] as $customerData) {
                    $this->processCustomer($customerData);
                }
            }

            // Process saving plans
            if (isset($data['saving_plans'])) {
                foreach ($data['saving_plans'] as $planData) {
                    $this->processSavingPlan($planData);
                }
            }

            // Process saving balances
            if (isset($data['saving_balances'])) {
                foreach ($data['saving_balances'] as $balanceData) {
                    $this->processSavingBalance($balanceData);
                }
            }

            // Process transactions
            if (isset($data['transactions'])) {
                foreach ($data['transactions'] as $transactionData) {
                    $this->processTransaction($transactionData);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process customer
     */
    protected function processCustomer($data)
    {
        return CustomerProfile::updateOrCreate(
            ['customer_id' => $data['customer_id']],
            [
                'customer_name' => $data['customer_name'] ?? '',
                'email_address' => $data['email_address'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'member_type' => $data['member_type'] ?? 'Full',
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'account_status' => $data['account_status'] ?? 'Active',
                'metadata' => $data['metadata'] ?? null
            ]
        );
    }

    /**
     * Process saving plan
     */
    protected function processSavingPlan($data)
    {
        return SavingPlan::updateOrCreate(
            ['memberid' => $data['memberid']],
            [
                'name' => $data['name'] ?? '',
                'membership' => $data['membership'] ?? null,
                'monthly_goal' => $data['monthly_goal'] ?? 0,
                'goal' => $data['goal'] ?? 0,
                'metadata' => $data['metadata'] ?? null
            ]
        );
    }

    /**
     * Process saving balance
     */
    protected function processSavingBalance($data)
    {
        return SavingBalance::updateOrCreate(
            ['customer_id' => $data['customer_id']],
            [
                'monthly_saving_target' => $data['monthly_saving_target'] ?? 0,
                'monthly_total_savings_deposits' => $data['monthly_total_savings_deposits'] ?? 0,
                'monthly_goal_achievement' => $data['monthly_goal_achievement'] ?? 0,
                'overall_saving_goal' => $data['overall_saving_goal'] ?? 0,
                'total_saved' => $data['total_saved'] ?? 0,
                'overall_goal_achievement' => $data['overall_goal_achievement'] ?? 0,
                'flexi_opening_balance' => $data['flexi_opening_balance'] ?? 0,
                'flexi_deposit' => $data['flexi_deposit'] ?? 0,
                'flexi_withdrawal' => $data['flexi_withdrawal'] ?? 0,
                'flexi_balance' => $data['flexi_balance'] ?? 0,
                'rda_opening_balance' => $data['rda_opening_balance'] ?? 0,
                'rda_deposit' => $data['rda_deposit'] ?? 0,
                'rda_withdrawal' => $data['rda_withdrawal'] ?? 0,
                'rda_balance' => $data['rda_balance'] ?? 0,
                'emergency_opening_balance' => $data['emergency_opening_balance'] ?? 0,
                'emergency_deposit' => $data['emergency_deposit'] ?? 0,
                'emergency_withdrawal' => $data['emergency_withdrawal'] ?? 0,
                'emergency_balance' => $data['emergency_balance'] ?? 0,
                'business_opening_balance' => $data['business_opening_balance'] ?? 0,
                'business_deposit' => $data['business_deposit'] ?? 0,
                'business_withdrawal' => $data['business_withdrawal'] ?? 0,
                'business_balance' => $data['business_balance'] ?? 0,
                'total_balance' => $data['total_balance'] ?? 0,
                'interest_payable' => $data['interest_payable'] ?? 0,
                'savings_held_for_loan_security' => $data['savings_held_for_loan_security'] ?? 0,
                'free_savings_emergency' => $data['free_savings_emergency'] ?? 0,
                'free_savings_rda_flexi_business' => $data['free_savings_rda_flexi_business'] ?? 0,
                'total_free_saving' => $data['total_free_saving'] ?? 0,
                'premature_withdraw_charge' => $data['premature_withdraw_charge'] ?? 0,
                'metadata' => $data['metadata'] ?? null
            ]
        );
    }

    /**
     * Process transaction
     */
    protected function processTransaction($data)
    {
        return Transaction::create([
            'membercode' => $data['customer_id'],
            'date' => $data['transaction_date'],
            'transaction_type' => $data['transaction_type'] ?? '',
            'reference_no' => $data['reference_no'] ?? null,
            'amount' => $data['amount'] ?? 0,
            'metadata' => $data['metadata'] ?? null
        ]);
    }

    /**
     * Call Google Sheets API
     */
    protected function callGoogleSheetsApi($action, $params = [])
    {
        try {
            // Try GET request first with parameters in query string
            $url = $this->apiUrl . '?' . http_build_query([
                'action' => $action,
                'type' => $params['type'] ?? 'all',
                'force' => $params['force'] ?? false,
                'source' => 'laravel_admin',
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->get($url);

            Log::info('Google Sheets API Response', [
                'url' => $url,
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500),
                'headers' => $response->headers()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            // If GET fails, try POST
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->post($this->apiUrl, [
                'action' => $action,
                'params' => $params,
                'source' => 'laravel_admin',
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);

            Log::info('Google Sheets API POST Response', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500),
                'headers' => $response->headers()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status() . ' - ' . substr($response->body(), 0, 200)
            ];

        } catch (\Exception $e) {
            Log::error("Google Sheets API call failed: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get sync status
     */
    public function getSyncStatus()
    {
        $lastSync = SyncLog::latest()->first();
        
        return [
            'last_sync' => $lastSync,
            'is_running' => $lastSync && $lastSync->status === 'running',
            'next_sync' => Carbon::now()->addHours(6)->toDateTimeString()
        ];
    }

    /**
     * Manual sync from uploaded data
     */
    public function manualSync($data)
    {
        $startTime = Carbon::now();
        $syncLog = SyncLog::create([
            'sync_type' => 'manual_sync',
            'started_at' => $startTime,
            'status' => 'running'
        ]);

        try {
            $this->processSyncedData($data);

            $syncLog->update([
                'completed_at' => Carbon::now(),
                'status' => 'completed',
                'records_synced' => count($data['customers'] ?? []) + count($data['saving_balances'] ?? []) + count($data['transactions'] ?? []),
                'records_failed' => 0,
                'summary' => $data
            ]);

            return [
                'success' => true,
                'log_id' => $syncLog->id,
                'message' => 'Manual sync completed successfully'
            ];

        } catch (\Exception $e) {
            $syncLog->update([
                'completed_at' => Carbon::now(),
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);

            Log::error('Manual sync failed: ' . $e->getMessage());

            return [
                'success' => false,
                'log_id' => $syncLog->id,
                'error' => $e->getMessage()
            ];
        }
    }
}
