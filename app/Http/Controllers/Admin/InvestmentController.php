<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Investment;
use App\Models\InvestmentProduct;
use App\Services\AdminDashboardService;
use App\Services\EncryptedIdService;
use App\Services\MemberService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InvestmentController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected MemberService $memberService,
        protected AdminDashboardService $dashboardService,
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request)
    {
        Gate::authorize('admin-only');

        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'investment_date');
        $sortDirection = $request->input('sort_direction', 'desc');
        $searchQuery = $request->input('q', '');
        $statusFilter = $request->input('status', '');

        $query = Investment::with(['user', 'investmentProduct']);

        // Search
        if (!empty($searchQuery)) {
            $query->where('investment_number', 'like', '%' . $searchQuery . '%')
                  ->orWhere('member_number', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('user', function ($q) use ($searchQuery) {
                      $q->where('name', 'like', '%' . $searchQuery . '%');
                  });
        }

        // Filter by status
        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        // Sort
        $query->orderBy($sortColumn, $sortDirection);

        $investments = $query->paginate($perPage);

        $investments->appends([
            'q' => $searchQuery,
            'status' => $statusFilter,
            'per_page' => $perPage,
            'sort' => $sortColumn,
            'sort_direction' => $sortDirection,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed investments list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $searchQuery,
                'status_filter' => $statusFilter,
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => $investments->total(),
            ],
        ]);

        return view('admin.investments.index', [
            'investments' => $investments,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, string $encryptedMemberNumber)
    {
        Gate::authorize('admin-only');

        $memberNumber = $this->encryptedIdService->decrypt($encryptedMemberNumber);

        $investments = Investment::with(['user', 'investmentProduct'])
            ->where('member_number', $memberNumber)
            ->orderBy('investment_date', 'desc')
            ->get();

        if ($investments->isEmpty()) {
            $this->error("No investments found for member {$memberNumber}");
            return redirect()->route('admin.investments.index');
        }

        $user = $investments->first()->user;
        $totalInvested = $investments->sum('amount');
        $totalCurrentValue = $investments->sum(function ($inv) {
            return $inv->actual_return ?? $inv->expected_return ?? 0;
        });
        $totalProfit = $totalCurrentValue - $totalInvested;
        $overallReturn = $totalInvested > 0 ? (($totalCurrentValue - $totalInvested) / $totalInvested) * 100 : 0;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'investment',
            'subject_id' => null,
            'description' => "Admin viewed member investments: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_name' => $user->name ?? null,
                'total_invested' => $totalInvested,
                'investment_count' => $investments->count(),
            ],
        ]);

        return view('admin.investments.show', [
            'member' => [
                'name' => $user->name ?? 'Unknown',
                'member_number' => $memberNumber,
                'email' => $user->email ?? null,
            ],
            'memberNumber' => $memberNumber,
            'investments' => $investments,
            'totalInvested' => $totalInvested,
            'totalCurrentValue' => $totalCurrentValue,
            'totalProfit' => $totalProfit,
            'overallReturn' => $overallReturn,
            'allHistory' => [],
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
