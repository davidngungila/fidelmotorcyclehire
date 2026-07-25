<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\AdminDashboardService;
use App\Services\MemberService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InvestmentController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $googleSheetRepository,
        protected MemberService $memberService,
        protected AdminDashboardService $dashboardService,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'member_number');
        $sortDirection = $request->input('sort_direction', 'asc');
        $searchQuery = $request->input('q', '');

        $allMembers = $this->googleSheetRepository->getAllMembers();

        $investmentsList = [];
        foreach ($allMembers as $member) {
            $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $investments = $this->googleSheetRepository->getMemberInvestments($memberNo);
            foreach ($investments as $inv) {
                $investmentsList[] = [
                    'member_number' => $memberNo,
                    'member_name' => $member['name'] ?? ($member['Name'] ?? 'Unknown'),
                    'member_branch' => $member['branch'] ?? ($member['Branch'] ?? '-'),
                    'product' => $inv['product'] ?? ($inv['Product'] ?? '-'),
                    'amount_invested' => (float) ($inv['amount_invested'] ?? ($inv['AmountInvested'] ?? 0)),
                    'units' => (float) ($inv['units'] ?? ($inv['Units'] ?? 0)),
                    'current_value' => (float) ($inv['current_value'] ?? ($inv['CurrentValue'] ?? 0)),
                    'profit_earned' => (float) ($inv['profit_earned'] ?? ($inv['ProfitEarned'] ?? 0)),
                    'return_rate' => (float) ($inv['return_rate'] ?? ($inv['ReturnRate'] ?? 0)),
                ];
            }
        }

        if (! empty($searchQuery)) {
            $investmentsList = array_values(array_filter($investmentsList, static function ($i) use ($searchQuery): bool {
                $query = strtolower(trim($searchQuery));
                $haystack = strtolower(implode(' ', [
                    $i['product'] ?? '',
                    $i['member_number'] ?? '',
                    $i['member_name'] ?? '',
                    $i['member_branch'] ?? '',
                ]));

                return str_contains($haystack, $query);
            }));
        }

        $investmentsList = $this->memberService->sort($investmentsList, $sortColumn, $sortDirection);
        $paginated = $this->memberService->paginateArray($investmentsList, $perPage);

        $paginated->appends([
            'q' => $searchQuery,
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
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => count($investmentsList),
            ],
        ]);

        return view('admin.investments.index', [
            'investments' => $paginated,
            'searchQuery' => $searchQuery,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, string $memberNumber)
    {
        Gate::authorize('admin-only');

        $member = $this->googleSheetRepository->getMemberByNumber($memberNumber);

        if (! $member) {
            $this->error("Member {$memberNumber} not found.");

            return redirect()->route('admin.investments.index');
        }

        $investments = $this->googleSheetRepository->getMemberInvestments($memberNumber);

        $totalInvested = 0;
        $totalCurrentValue = 0;
        $totalProfit = 0;

        foreach ($investments as $inv) {
            $invested = (float) ($inv['amount_invested'] ?? ($inv['AmountInvested'] ?? 0));
            $current = (float) ($inv['current_value'] ?? ($inv['CurrentValue'] ?? 0));
            $totalInvested += $invested;
            $totalCurrentValue += $current;
            $totalProfit += ($current - $invested);
        }

        $overallReturn = $totalInvested > 0 ? (($totalCurrentValue - $totalInvested) / $totalInvested) * 100 : 0;

        $allHistory = [];
        foreach ($investments as $inv) {
            $product = $inv['product'] ?? ($inv['Product'] ?? 'Unknown');
            $history = $inv['history'] ?? ($inv['History'] ?? []);
            foreach ($history as $h) {
                $allHistory[] = array_merge($h, [
                    'product' => $product,
                ]);
            }
        }

        usort($allHistory, static function ($a, $b): int {
            $dateA = $a['date'] ?? '';
            $dateB = $b['date'] ?? '';

            return strcmp($dateB, $dateA);
        });

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'investment',
            'subject_id' => null,
            'description' => "Admin viewed member investments: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_name' => $member['name'] ?? null,
                'total_invested' => $totalInvested,
                'investment_count' => count($investments),
            ],
        ]);

        return view('admin.investments.show', [
            'member' => $member,
            'memberNumber' => $memberNumber,
            'investments' => $investments,
            'totalInvested' => $totalInvested,
            'totalCurrentValue' => $totalCurrentValue,
            'totalProfit' => $totalProfit,
            'overallReturn' => $overallReturn,
            'allHistory' => $allHistory,
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
