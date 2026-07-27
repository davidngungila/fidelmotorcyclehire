<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\SavingBalance;
use App\Models\Transaction;
use App\Models\SyncLog;
use App\Services\GoogleSheetsSyncService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class GoogleSheetsController extends Controller
{
    use FlashMessages;

    protected $syncService;

    public function __construct(GoogleSheetsSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display Google Sheets dashboard
     */
    public function index(Request $request)
    {
        $data = [
            'total_customers' => CustomerProfile::count(),
            'active_customers' => CustomerProfile::where('account_status', 'Active')->count(),
            'total_balance' => SavingBalance::sum('total_balance'),
            'last_sync' => SyncLog::latest()->first(),
            'sync_logs' => SyncLog::latest()->limit(10)->get(),
            'sync_stats' => $this->getSyncStats(),
            'account_breakdown' => $this->getAccountBreakdown()
        ];

        return view('admin.google-sheets.index', $data);
    }

    /**
     * Trigger manual sync
     */
    public function sync(Request $request)
    {
        try {
            $type = $request->input('type', 'all');
            $force = $request->input('force', false);

            Artisan::call('sync:google-sheets', [
                '--' . $type => true,
                '--force' => $force
            ]);

            $output = Artisan::output();

            $this->success('Sync triggered successfully');

            return response()->json([
                'success' => true,
                'message' => 'Sync triggered successfully',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            $this->error('Failed to trigger sync: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to trigger sync',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sync status
     */
    public function status()
    {
        $lastSync = SyncLog::latest()->first();
        $isRunning = $lastSync && $lastSync->status === 'running';

        return response()->json([
            'success' => true,
            'data' => [
                'last_sync' => $lastSync,
                'is_running' => $isRunning,
                'next_sync' => now()->addHours(6)->toDateTimeString(),
                'sync_schedule' => 'Every 6 hours'
            ]
        ]);
    }

    /**
     * Get sync logs
     */
    public function logs(Request $request)
    {
        if ($request->wantsJson()) {
            $limit = $request->input('limit', 50);
            $type = $request->input('type');
            $status = $request->input('status');

            $query = SyncLog::query();

            if ($type) {
                $query->where('sync_type', $type);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $logs = $query->latest()->limit($limit)->get();

            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        }

        return view('admin.google-sheets.logs');
    }

    /**
     * Get customers data
     */
    public function customers(Request $request)
    {
        if ($request->wantsJson()) {
            $query = CustomerProfile::with(['savingBalance', 'transactions' => function($q) {
                $q->latest()->limit(5);
            }]);

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('customer_id', 'LIKE', "%{$search}%")
                      ->orWhere('customer_name', 'LIKE', "%{$search}%")
                      ->orWhere('email_address', 'LIKE', "%{$search}%")
                      ->orWhere('phone_number', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('account_status', $request->status);
            }

            if ($request->has('member_type')) {
                $query->where('member_type', $request->member_type);
            }

            $customers = $query->paginate($request->input('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $customers
            ]);
        }

        return view('admin.google-sheets.customers');
    }

    /**
     * Get customer details
     */
    public function customer($customerId)
    {
        $customer = CustomerProfile::with(['savingBalance', 'transactions', 'savingPlan'])
            ->where('customer_id', $customerId)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }

    /**
     * Get dashboard summary
     */
    public function summary()
    {
        $summary = [
            'total_customers' => CustomerProfile::count(),
            'active_customers' => CustomerProfile::where('account_status', 'Active')->count(),
            'total_balance' => SavingBalance::sum('total_balance'),
            'account_breakdown' => $this->getAccountBreakdown(),
            'transactions_summary' => [
                'today' => Transaction::whereDate('date', today())->count(),
                'this_week' => Transaction::whereBetween('date', [now()->startOfWeek(), now()])->count(),
                'this_month' => Transaction::whereMonth('date', now()->month)->count(),
                'total_amount' => Transaction::sum('amount')
            ],
            'savings_goals' => [
                'total_goal' => SavingBalance::sum('overall_saving_goal'),
                'total_saved' => SavingBalance::sum('total_saved'),
                'achievement_rate' => $this->getOverallAchievementRate()
            ],
            'sync_stats' => $this->getSyncStats()
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Manual sync from uploaded data
     */
    public function manualSync(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->syncService->manualSync($data);

            if ($result['success']) {
                $this->success('Manual sync completed successfully');
            } else {
                $this->error('Manual sync failed: ' . $result['error']);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            $this->error('Manual sync failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get account breakdown
     */
    protected function getAccountBreakdown()
    {
        return [
            'flexi' => SavingBalance::sum('flexi_balance'),
            'rda' => SavingBalance::sum('rda_balance'),
            'emergency' => SavingBalance::sum('emergency_balance'),
            'business' => SavingBalance::sum('business_balance'),
            'total' => SavingBalance::sum('total_balance')
        ];
    }

    /**
     * Get sync statistics
     */
    protected function getSyncStats()
    {
        $today = SyncLog::whereDate('created_at', today());
        $thisWeek = SyncLog::whereBetween('created_at', [now()->startOfWeek(), now()]);
        $thisMonth = SyncLog::whereMonth('created_at', now()->month);
        $total = SyncLog::count();

        return [
            'today' => $today->count(),
            'today_success' => $today->where('status', 'completed')->count(),
            'today_failed' => $today->where('status', 'failed')->count(),
            'this_week' => $thisWeek->count(),
            'this_month' => $thisMonth->count(),
            'total' => $total,
            'success_rate' => $total > 0 ? round(($thisMonth->where('status', 'completed')->count() / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get overall achievement rate
     */
    protected function getOverallAchievementRate()
    {
        $totalGoal = SavingBalance::sum('overall_saving_goal');
        $totalSaved = SavingBalance::sum('total_saved');
        
        if ($totalGoal > 0) {
            return round(($totalSaved / $totalGoal) * 100, 2);
        }
        return 0;
    }
}
