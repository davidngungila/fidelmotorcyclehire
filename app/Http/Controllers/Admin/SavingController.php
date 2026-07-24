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

class SavingController extends Controller
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

        $savingsList = [];
        foreach ($allMembers as $member) {
            $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $savings = $this->googleSheetRepository->getMemberSavings($memberNo);

            $balance = (float) ($savings['balance'] ?? 0);
            $interestEarned = (float) ($savings['interest_earned'] ?? 0);
            $runningBalance = (float) ($savings['running_balance'] ?? ($balance + $interestEarned));
            $transactions = $savings['transactions'] ?? [];

            $lastTransaction = null;
            if (! empty($transactions)) {
                $lastTransaction = $transactions[0]['date'] ?? null;
            }

            $savingsList[] = [
                'member_number' => $memberNo,
                'member_name' => $member['name'] ?? ($member['Name'] ?? 'Unknown'),
                'member_status' => $member['status'] ?? 'Active',
                'member_branch' => $member['branch'] ?? ($member['Branch'] ?? '-'),
                'balance' => $balance,
                'interest_earned' => $interestEarned,
                'running_balance' => $runningBalance,
                'last_transaction' => $lastTransaction ?? '-',
                'transactions_count' => count($transactions),
            ];
        }

        if (! empty($searchQuery)) {
            $savingsList = array_values(array_filter($savingsList, static function ($s) use ($searchQuery): bool {
                $query = strtolower(trim($searchQuery));
                $haystack = strtolower(implode(' ', [
                    $s['member_number'] ?? '',
                    $s['member_name'] ?? '',
                    $s['member_branch'] ?? '',
                ]));

                return str_contains($haystack, $query);
            }));
        }

        $savingsList = $this->memberService->sort($savingsList, $sortColumn, $sortDirection);
        $paginated = $this->memberService->paginateArray($savingsList, $perPage);

        $paginated->appends([
            'q' => $searchQuery,
            'per_page' => $perPage,
            'sort' => $sortColumn,
            'sort_direction' => $sortDirection,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed savings list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $searchQuery,
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => count($savingsList),
            ],
        ]);

        return view('admin.savings.index', [
            'savings' => $paginated,
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

            return redirect()->route('admin.savings.index');
        }

        $savings = $this->googleSheetRepository->getMemberSavings($memberNumber);

        $balance = (float) ($savings['balance'] ?? 0);
        $interestEarned = (float) ($savings['interest_earned'] ?? 0);
        $runningBalance = (float) ($savings['running_balance'] ?? ($balance + $interestEarned));
        $transactions = $savings['transactions'] ?? [];

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'savings',
            'subject_id' => null,
            'description' => "Admin viewed member savings: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_name' => $member['name'] ?? null,
                'balance' => $balance,
                'transactions_count' => count($transactions),
            ],
        ]);

        return view('admin.savings.show', [
            'member' => $member,
            'memberNumber' => $memberNumber,
            'savings' => $savings,
            'balance' => $balance,
            'interestEarned' => $interestEarned,
            'runningBalance' => $runningBalance,
            'transactions' => $transactions,
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
