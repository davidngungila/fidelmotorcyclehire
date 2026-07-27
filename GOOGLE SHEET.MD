Complete Google Sheets to Laravel Admin Sync System
Based on your requirements, I'll create a complete backend system to manage and sync all Google Sheets data through the admin panel at /admin/google-sheets.

Part 1: Database Migrations
1.1 Customer Profiles Table
php
<?php
// database/migrations/2024_01_01_000000_create_customer_profiles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique();
            $table->string('customer_name');
            $table->string('email_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('member_type')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('account_status')->default('Active');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('customer_id');
            $table->index('member_type');
            $table->index('account_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_profiles');
    }
};
1.2 Saving Plans Table
php
<?php
// database/migrations/2024_01_01_000001_create_saving_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saving_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('plan_id')->unique();
            $table->string('membership')->nullable();
            $table->decimal('monthly_goal', 15, 2)->default(0);
            $table->decimal('goal', 15, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('plan_id');
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('saving_plans');
    }
};
1.3 Saving Balances Table
php
<?php
// database/migrations/2024_01_01_000002_create_saving_balances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saving_balances', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            
            // Monthly Savings (Columns B-G)
            $table->decimal('monthly_saving_target', 15, 2)->default(0);
            $table->decimal('monthly_total_savings_deposits', 15, 2)->default(0);
            $table->decimal('monthly_goal_achievement', 10, 2)->default(0);
            $table->decimal('overall_saving_goal', 15, 2)->default(0);
            $table->decimal('total_saved', 15, 2)->default(0);
            $table->decimal('overall_goal_achievement', 10, 2)->default(0);
            
            // Flexi Account (Columns H-K)
            $table->decimal('flexi_opening_balance', 15, 2)->default(0);
            $table->decimal('flexi_deposit', 15, 2)->default(0);
            $table->decimal('flexi_withdrawal', 15, 2)->default(0);
            $table->decimal('flexi_balance', 15, 2)->default(0);
            
            // RDA Account (Columns L-O)
            $table->decimal('rda_opening_balance', 15, 2)->default(0);
            $table->decimal('rda_deposit', 15, 2)->default(0);
            $table->decimal('rda_withdrawal', 15, 2)->default(0);
            $table->decimal('rda_balance', 15, 2)->default(0);
            
            // Emergency Account (Columns P-S)
            $table->decimal('emergency_opening_balance', 15, 2)->default(0);
            $table->decimal('emergency_deposit', 15, 2)->default(0);
            $table->decimal('emergency_withdrawal', 15, 2)->default(0);
            $table->decimal('emergency_balance', 15, 2)->default(0);
            
            // Business Account (Columns T-U)
            $table->decimal('business_opening_balance', 15, 2)->default(0);
            $table->decimal('business_deposit', 15, 2)->default(0);
            $table->decimal('business_withdrawal', 15, 2)->default(0);
            $table->decimal('business_balance', 15, 2)->default(0);
            
            // Totals (Columns V-AD)
            $table->decimal('total_balance', 15, 2)->default(0);
            $table->decimal('interest_payable', 15, 2)->default(0);
            $table->decimal('savings_held_for_loan_security', 15, 2)->default(0);
            $table->decimal('free_savings_emergency', 15, 2)->default(0);
            $table->decimal('free_savings_rda_flexi_business', 15, 2)->default(0);
            $table->decimal('total_free_saving', 15, 2)->default(0);
            $table->decimal('premature_withdraw_charge', 15, 2)->default(0);
            
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')->references('customer_id')->on('customer_profiles')->onDelete('cascade');
            $table->index('customer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('saving_balances');
    }
};
1.4 Transactions Table
php
<?php
// database/migrations/2024_01_01_000003_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->date('transaction_date');
            $table->string('transaction_type');
            $table->string('reference_no')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('account_type')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')->references('customer_id')->on('customer_profiles')->onDelete('cascade');
            $table->index('customer_id');
            $table->index('transaction_date');
            $table->index('transaction_type');
            $table->index('account_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
1.5 Sync Logs Table
php
<?php
// database/migrations/2024_01_01_000004_create_sync_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sync_type');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->string('status')->default('running');
            $table->integer('records_synced')->default(0);
            $table->integer('records_failed')->default(0);
            $table->json('summary')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
            
            $table->index('sync_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sync_logs');
    }
};
Part 2: Laravel Models
2.1 CustomerProfile Model
php
<?php
// app/Models/CustomerProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProfile extends Model
{
    use SoftDeletes;

    protected $table = 'customer_profiles';

    protected $fillable = [
        'customer_id', 'customer_name', 'email_address',
        'phone_number', 'member_type', 'start_date',
        'end_date', 'account_status', 'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'metadata' => 'array'
    ];

    public function savingBalance(): HasOne
    {
        return $this->hasOne(SavingBalance::class, 'customer_id', 'customer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'customer_id');
    }

    public function savingPlan(): HasOne
    {
        return $this->hasOne(SavingPlan::class, 'plan_id', 'customer_id');
    }

    public function scopeActive($query)
    {
        return $query->where('account_status', 'Active');
    }

    public function scopeByMemberType($query, $type)
    {
        return $query->where('member_type', $type);
    }

    public function getTotalBalanceAttribute()
    {
        return $this->savingBalance ? $this->savingBalance->total_balance : 0;
    }

    public function getAccountBalancesAttribute()
    {
        if (!$this->savingBalance) {
            return ['flexi' => 0, 'rda' => 0, 'emergency' => 0, 'business' => 0];
        }
        
        return [
            'flexi' => $this->savingBalance->flexi_balance,
            'rda' => $this->savingBalance->rda_balance,
            'emergency' => $this->savingBalance->emergency_balance,
            'business' => $this->savingBalance->business_balance,
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->customer_name;
    }

    public function getPhoneAttribute()
    {
        return $this->phone_number;
    }

    public function getEmailAttribute()
    {
        return $this->email_address;
    }
}
2.2 SavingPlan Model
php
<?php
// app/Models/SavingPlan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingPlan extends Model
{
    protected $table = 'saving_plans';

    protected $fillable = [
        'name', 'plan_id', 'membership', 'monthly_goal', 'goal', 'metadata'
    ];

    protected $casts = [
        'monthly_goal' => 'decimal:2',
        'goal' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'plan_id', 'customer_id');
    }

    public function scopeByMembership($query, $membership)
    {
        return $query->where('membership', $membership);
    }
}
2.3 SavingBalance Model
php
<?php
// app/Models/SavingBalance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingBalance extends Model
{
    protected $table = 'saving_balances';

    protected $fillable = [
        'customer_id',
        'monthly_saving_target', 'monthly_total_savings_deposits',
        'monthly_goal_achievement', 'overall_saving_goal',
        'total_saved', 'overall_goal_achievement',
        'flexi_opening_balance', 'flexi_deposit', 'flexi_withdrawal', 'flexi_balance',
        'rda_opening_balance', 'rda_deposit', 'rda_withdrawal', 'rda_balance',
        'emergency_opening_balance', 'emergency_deposit', 'emergency_withdrawal', 'emergency_balance',
        'business_opening_balance', 'business_deposit', 'business_withdrawal', 'business_balance',
        'total_balance', 'interest_payable', 'savings_held_for_loan_security',
        'free_savings_emergency', 'free_savings_rda_flexi_business',
        'total_free_saving', 'premature_withdraw_charge', 'metadata'
    ];

    protected $casts = [
        'monthly_saving_target' => 'decimal:2',
        'monthly_total_savings_deposits' => 'decimal:2',
        'monthly_goal_achievement' => 'decimal:2',
        'overall_saving_goal' => 'decimal:2',
        'total_saved' => 'decimal:2',
        'overall_goal_achievement' => 'decimal:2',
        'flexi_opening_balance' => 'decimal:2',
        'flexi_deposit' => 'decimal:2',
        'flexi_withdrawal' => 'decimal:2',
        'flexi_balance' => 'decimal:2',
        'rda_opening_balance' => 'decimal:2',
        'rda_deposit' => 'decimal:2',
        'rda_withdrawal' => 'decimal:2',
        'rda_balance' => 'decimal:2',
        'emergency_opening_balance' => 'decimal:2',
        'emergency_deposit' => 'decimal:2',
        'emergency_withdrawal' => 'decimal:2',
        'emergency_balance' => 'decimal:2',
        'business_opening_balance' => 'decimal:2',
        'business_deposit' => 'decimal:2',
        'business_withdrawal' => 'decimal:2',
        'business_balance' => 'decimal:2',
        'total_balance' => 'decimal:2',
        'interest_payable' => 'decimal:2',
        'savings_held_for_loan_security' => 'decimal:2',
        'free_savings_emergency' => 'decimal:2',
        'free_savings_rda_flexi_business' => 'decimal:2',
        'total_free_saving' => 'decimal:2',
        'premature_withdraw_charge' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id', 'customer_id');
    }

    public function getCombinedOpeningBalanceAttribute()
    {
        return $this->flexi_opening_balance + 
               $this->rda_opening_balance + 
               $this->emergency_opening_balance + 
               $this->business_opening_balance;
    }

    public function getTotalDepositsAttribute()
    {
        return $this->flexi_deposit + 
               $this->rda_deposit + 
               $this->emergency_deposit + 
               $this->business_deposit;
    }

    public function getTotalWithdrawalsAttribute()
    {
        return $this->flexi_withdrawal + 
               $this->rda_withdrawal + 
               $this->emergency_withdrawal + 
               $this->business_withdrawal;
    }

    public function getGoalAchievementRateAttribute()
    {
        if ($this->overall_saving_goal > 0) {
            return round(($this->total_saved / $this->overall_saving_goal) * 100, 2);
        }
        return 0;
    }
}
2.4 Transaction Model
php
<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'customer_id', 'transaction_date', 'transaction_type',
        'reference_no', 'amount', 'account_type', 'metadata'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id', 'customer_id');
    }

    public function scopeByAccount($query, $accountType)
    {
        return $query->where('account_type', $accountType);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getTransactionDateFormattedAttribute()
    {
        return $this->transaction_date->format('d/m/Y');
    }
}
2.5 SyncLog Model
php
<?php
// app/Models/SyncLog.php

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
Part 3: Admin Controllers
3.1 Admin Google Sheets Controller
php
<?php
// app/Http/Controllers/Admin/GoogleSheetsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\SavingBalance;
use App\Models\Transaction;
use App\Models\SyncLog;
use App\Services\GoogleSheetsSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoogleSheetsController extends Controller
{
    protected $syncService;

    public function __construct(GoogleSheetsSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display Google Sheets dashboard
     */
    public function index()
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

            return response()->json([
                'success' => true,
                'message' => 'Sync triggered successfully',
                'output' => $output
            ]);

        } catch (\Exception $e) {
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

    /**
     * Get customers data
     */
    public function customers(Request $request)
    {
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
                'today' => Transaction::whereDate('transaction_date', today())->count(),
                'this_week' => Transaction::whereBetween('transaction_date', [now()->startOfWeek(), now()])->count(),
                'this_month' => Transaction::whereMonth('transaction_date', now()->month)->count(),
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
Part 4: Admin Routes
php
<?php
// routes/admin.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GoogleSheetsController;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/google-sheets', [GoogleSheetsController::class, 'index'])->name('google-sheets.index');
    Route::post('/google-sheets/sync', [GoogleSheetsController::class, 'sync'])->name('google-sheets.sync');
    Route::get('/google-sheets/status', [GoogleSheetsController::class, 'status'])->name('google-sheets.status');
    Route::get('/google-sheets/logs', [GoogleSheetsController::class, 'logs'])->name('google-sheets.logs');
    Route::get('/google-sheets/customers', [GoogleSheetsController::class, 'customers'])->name('google-sheets.customers');
    Route::get('/google-sheets/customers/{customerId}', [GoogleSheetsController::class, 'customer'])->name('google-sheets.customer');
    Route::get('/google-sheets/summary', [GoogleSheetsController::class, 'summary'])->name('google-sheets.summary');
});
Part 5: Service Class
php
<?php
// app/Services/GoogleSheetsSyncService.php

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
    protected $apiKey;
    protected $batchSize = 100;

    public function __construct()
    {
        $this->apiUrl = config('services.google_sheets.api_url');
        $this->apiKey = config('services.google_sheets.api_key');
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
            ['plan_id' => $data['plan_id']],
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
            'customer_id' => $data['customer_id'],
            'transaction_date' => $data['transaction_date'],
            'transaction_type' => $data['transaction_type'] ?? '',
            'reference_no' => $data['reference_no'] ?? null,
            'amount' => $data['amount'] ?? 0,
            'account_type' => $data['account_type'] ?? 'flexi',
            'metadata' => $data['metadata'] ?? null
        ]);
    }

    /**
     * Call Google Sheets API
     */
    protected function callGoogleSheetsApi($action, $params = [])
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json'
            ])->post($this->apiUrl, [
                'action' => $action,
                'params' => $params,
                'source' => 'laravel_admin',
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status()
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
}
Part 6: Console Commands
6.1 Sync Command
php
<?php
// app/Console/Commands/SyncGoogleSheets.php

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
Part 7: Kernel Configuration
7.1 Schedule Kernel
php
<?php
// app/Console/Kernel.php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Sync every 6 hours
        $schedule->command('sync:google-sheets --all')
            ->everySixHours()
            ->between('6:00', '23:00')
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/sync-google-sheets.log'));

        // Clean up old sync logs every day
        $schedule->command('sync:clean-logs')
            ->daily()
            ->at('01:00')
            ->runInBackground();

        // Send sync report every 6 hours
        $schedule->command('sync:send-report')
            ->everySixHours()
            ->runInBackground();
    }
}
7.2 Clean Logs Command
php
<?php
// app/Console/Commands/CleanSyncLogs.php

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
Part 8: Configuration
8.1 Services Config
php
<?php
// config/services.php

return [
    'google_sheets' => [
        'api_url' => env('GOOGLE_SHEETS_API_URL'),
        'api_key' => env('GOOGLE_SHEETS_API_KEY'),
        'sheet_id' => env('GOOGLE_SHEETS_SHEET_ID'),
        'batch_size' => env('GOOGLE_SHEETS_BATCH_SIZE', 100),
        'sync_interval' => env('GOOGLE_SHEETS_SYNC_INTERVAL', 360),
    ],
];
8.2 .env Variables
env
# Google Sheets Configuration
GOOGLE_SHEETS_API_URL=https://script.google.com/macros/s/your-script-id/exec
GOOGLE_SHEETS_API_KEY=your-secret-api-key
GOOGLE_SHEETS_SHEET_ID=1BTEPceHVgfQe4SRdDmRO_3bV6k8jA_2lWCqglDfiPwU
GOOGLE_SHEETS_BATCH_SIZE=100
GOOGLE_SHEETS_SYNC_INTERVAL=360
Part 9: API Routes
php
<?php
// routes/api.php

use App\Http\Controllers\Api\GoogleSheetsReceiverController;
use App\Http\Controllers\Admin\GoogleSheetsController;

Route::prefix('api/v1')->group(function () {
    // Google Sheets Receiver Endpoints (from Google Apps Script)
    Route::post('/sync/full-data', [GoogleSheetsReceiverController::class, 'syncFullData']);
    Route::post('/sync/customers', [GoogleSheetsReceiverController::class, 'syncCustomers']);
    Route::post('/sync/transactions', [GoogleSheetsReceiverController::class, 'syncTransactions']);
    Route::post('/sync/balances', [GoogleSheetsReceiverController::class, 'syncBalances']);
    Route::post('/sync/saving-plans', [GoogleSheetsReceiverController::class, 'syncSavingPlans']);
    
    // Admin API Endpoints
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/admin/sync/trigger', [GoogleSheetsController::class, 'sync']);
        Route::get('/admin/sync/status', [GoogleSheetsController::class, 'status']);
        Route::get('/admin/sync/logs', [GoogleSheetsController::class, 'logs']);
        Route::get('/admin/customers', [GoogleSheetsController::class, 'customers']);
        Route::get('/admin/customers/{customerId}', [GoogleSheetsController::class, 'customer']);
        Route::get('/admin/summary', [GoogleSheetsController::class, 'summary']);
    });
});
Part 10: API Receiver Controller
php
<?php
// app/Http/Controllers/Api/GoogleSheetsReceiverController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\SavingBalance;
use App\Models\SavingPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoogleSheetsReceiverController extends Controller
{
    public function syncFullData(Request $request)
    {
        $apiKey = $request->header('X-API-Key');
        if ($apiKey !== config('services.google_sheets.api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        DB::beginTransaction();

        try {
            $results = [
                'customers' => 0,
                'saving_plans' => 0,
                'saving_balances' => 0,
                'transactions' => 0
            ];

            // Process Customers
            if ($request->has('customers')) {
                foreach ($request->customers as $data) {
                    CustomerProfile::updateOrCreate(
                        ['customer_id' => $data['customer_id']],
                        [
                            'customer_name' => $data['customer_name'] ?? '',
                            'email_address' => $data['email_address'] ?? null,
                            'phone_number' => $data['phone_number'] ?? null,
                            'member_type' => $data['member_type'] ?? 'Full',
                            'start_date' => $data['start_date'] ?? null,
                            'end_date' => $data['end_date'] ?? null,
                            'account_status' => $data['account_status'] ?? 'Active'
                        ]
                    );
                    $results['customers']++;
                }
            }

            // Process Saving Plans
            if ($request->has('saving_plans')) {
                foreach ($request->saving_plans as $data) {
                    SavingPlan::updateOrCreate(
                        ['plan_id' => $data['plan_id']],
                        [
                            'name' => $data['name'] ?? '',
                            'membership' => $data['membership'] ?? null,
                            'monthly_goal' => $data['monthly_goal'] ?? 0,
                            'goal' => $data['goal'] ?? 0
                        ]
                    );
                    $results['saving_plans']++;
                }
            }

            // Process Saving Balances
            if ($request->has('saving_balances')) {
                foreach ($request->saving_balances as $data) {
                    SavingBalance::updateOrCreate(
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
                            'premature_withdraw_charge' => $data['premature_withdraw_charge'] ?? 0
                        ]
                    );
                    $results['saving_balances']++;
                }
            }

            // Process Transactions
            if ($request->has('transactions')) {
                foreach ($request->transactions as $data) {
                    Transaction::create([
                        'customer_id' => $data['customer_id'],
                        'transaction_date' => $data['transaction_date'],
                        'transaction_type' => $data['transaction_type'] ?? '',
                        'reference_no' => $data['reference_no'] ?? null,
                        'amount' => $data['amount'] ?? 0,
                        'account_type' => $data['account_type'] ?? 'flexi'
                    ]);
                    $results['transactions']++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data synced successfully',
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sync error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
Part 11: Middleware for Admin
php
<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }
}
11.1 Register Middleware
php
<?php
// app/Http/Kernel.php

protected $routeMiddleware = [
    // ... other middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
Summary
This complete backend system provides:

Admin Dashboard at /admin/google-sheets with full management capabilities

Database Schema for all Google Sheets data

Models with relationships and scopes

Admin Controllers for managing sync operations

API Receiver for Google Apps Script webhook

Console Commands for scheduled sync

Services for business logic

Middleware for admin authentication

Configuration for environment variables

API Routes for both admin and receiver endpoints