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

class DepositController extends Controller
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
        $sortColumn = $request->input('sort', 'certificate_number');
        $sortDirection = $request->input('sort_direction', 'asc');
        $statusFilter = $request->input('status', '');
        $searchQuery = $request->input('q', '');

        $allMembers = $this->googleSheetRepository->getAllMembers();

        $depositsList = [];
        foreach ($allMembers as $member) {
            $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $deposits = $this->googleSheetRepository->getMemberDeposits($memberNo);
            foreach ($deposits as $dep) {
                $dep['member_number'] = $memberNo;
                $dep['member_name'] = $member['name'] ?? ($member['Name'] ?? 'Unknown');
                $dep['member_branch'] = $member['branch'] ?? ($member['Branch'] ?? '-');
                $depositsList[] = $dep;
            }
        }

        if (! empty($searchQuery)) {
            $depositsList = array_values(array_filter($depositsList, static function ($d) use ($searchQuery): bool {
                $query = strtolower(trim($searchQuery));
                $haystack = strtolower(implode(' ', [
                    $d['certificate_number'] ?? '',
                    $d['product'] ?? '',
                    $d['member_number'] ?? '',
                    $d['member_name'] ?? '',
                    $d['member_branch'] ?? '',
                ]));

                return str_contains($haystack, $query);
            }));
        }

        if (! empty($statusFilter)) {
            $depositsList = $this->memberService->filterByStatus($depositsList, $statusFilter);
        }

        $depositsList = $this->memberService->sort($depositsList, $sortColumn, $sortDirection);
        $paginated = $this->memberService->paginateArray($depositsList, $perPage);

        $paginated->appends([
            'q' => $searchQuery,
            'status' => $statusFilter,
            'per_page' => $perPage,
            'sort' => $sortColumn,
            'sort_direction' => $sortDirection,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed deposits list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $searchQuery,
                'status_filter' => $statusFilter,
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => count($depositsList),
            ],
        ]);

        return view('admin.deposits.index', [
            'deposits' => $paginated,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, string $certificateNumber)
    {
        Gate::authorize('admin-only');

        $allMembers = $this->googleSheetRepository->getAllMembers();
        $deposit = null;
        $member = null;

        foreach ($allMembers as $m) {
            $memberNo = $m['member_number'] ?? ($m['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $deposits = $this->googleSheetRepository->getMemberDeposits($memberNo);
            foreach ($deposits as $d) {
                $currentCert = $d['certificate_number'] ?? ($d['CertificateNumber'] ?? null);
                if ($currentCert === $certificateNumber) {
                    $deposit = $d;
                    $member = $m;
                    break 2;
                }
            }
        }

        if (! $deposit) {
            $this->error("Deposit certificate {$certificateNumber} not found.");

            return redirect()->route('admin.deposits.index');
        }

        $amount = (float) ($deposit['amount'] ?? ($deposit['Amount'] ?? 0));
        $interest = (float) ($deposit['interest'] ?? ($deposit['Interest'] ?? 0));
        $currentValue = (float) ($deposit['current_value'] ?? ($deposit['CurrentValue'] ?? 0));
        $startDate = $deposit['start_date'] ?? ($deposit['StartDate'] ?? '-');
        $maturityDate = $deposit['maturity_date'] ?? ($deposit['MaturityDate'] ?? '-');

        $progress = 0;
        if ($startDate !== '-' && $maturityDate !== '-') {
            $startTs = strtotime($startDate);
            $maturityTs = strtotime($maturityDate);
            $now = time();
            if ($startTs && $maturityTs && $maturityTs > $startTs) {
                $progress = min(100, max(0, (($now - $startTs) / ($maturityTs - $startTs)) * 100));
            }
        }

        $interestRate = 0;
        if ($amount > 0) {
            $interestRate = ($interest / $amount) * 100;
        }

        $timeline = [];
        if ($startDate !== '-') {
            $timeline[] = [
                'date' => $startDate,
                'title' => 'Placement Date',
                'description' => "Fixed deposit placed with principal of {$amount} TSh",
                'icon' => 'fa-money-bill-transfer',
                'color' => 'primary',
            ];
        }
        if ($progress >= 25 && $progress < 100) {
            $checkpointDate = date('Y-m-d', strtotime($startDate . ' + ' . (int) ((strtotime($maturityDate) - strtotime($startDate)) / 4 / 86400) . ' days'));
            $timeline[] = [
                'date' => $checkpointDate,
                'title' => 'Quarterly Accrual',
                'description' => 'Interest accrued and compounded',
                'icon' => 'fa-percent',
                'color' => 'yellow',
            ];
        }
        if ($progress >= 50 && $progress < 100) {
            $checkpointDate = date('Y-m-d', strtotime($startDate . ' + ' . (int) ((strtotime($maturityDate) - strtotime($startDate)) / 2 / 86400) . ' days'));
            $timeline[] = [
                'date' => $checkpointDate,
                'title' => 'Mid-term Checkpoint',
                'description' => "Current value: {$currentValue} TSh",
                'icon' => 'fa-chart-column',
                'color' => 'blue',
            ];
        }
        if ($maturityDate !== '-') {
            $timeline[] = [
                'date' => $maturityDate,
                'title' => 'Maturity Date',
                'description' => $progress >= 100 ? 'Deposit matured - ready for withdrawal or rollover' : 'Deposit will mature on this date',
                'icon' => 'fa-flag-checkered',
                'color' => $progress >= 100 ? 'green' : 'purple',
            ];
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'deposit',
            'subject_id' => $certificateNumber,
            'description' => "Admin viewed deposit: {$certificateNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_number' => $member['member_number'] ?? null,
                'member_name' => $member['name'] ?? null,
                'amount' => $amount,
            ],
        ]);

        return view('admin.deposits.show', [
            'deposit' => $deposit,
            'certificateNumber' => $certificateNumber,
            'member' => $member,
            'amount' => $amount,
            'interest' => $interest,
            'interestRate' => $interestRate,
            'currentValue' => $currentValue,
            'startDate' => $startDate,
            'maturityDate' => $maturityDate,
            'progress' => $progress,
            'timeline' => $timeline,
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
