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

class SwfController extends Controller
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

        $swfList = [];
        foreach ($allMembers as $member) {
            $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $swf = $this->googleSheetRepository->getMemberSwf($memberNo);

            $swfList[] = [
                'member_number' => $memberNo,
                'member_name' => $member['name'] ?? ($member['Name'] ?? 'Unknown'),
                'member_status' => $member['status'] ?? 'Active',
                'member_branch' => $member['branch'] ?? ($member['Branch'] ?? '-'),
                'total_contribution' => (float) ($swf['total_contribution'] ?? 0),
                'benefits' => (float) ($swf['benefits'] ?? 0),
                'current_balance' => (float) ($swf['current_balance'] ?? 0),
                'contributions_count' => count($swf['contribution_history'] ?? []),
            ];
        }

        if (! empty($searchQuery)) {
            $swfList = array_values(array_filter($swfList, static function ($s) use ($searchQuery): bool {
                $query = strtolower(trim($searchQuery));
                $haystack = strtolower(implode(' ', [
                    $s['member_number'] ?? '',
                    $s['member_name'] ?? '',
                    $s['member_branch'] ?? '',
                ]));

                return str_contains($haystack, $query);
            }));
        }

        $swfList = $this->memberService->sort($swfList, $sortColumn, $sortDirection);
        $paginated = $this->memberService->paginateArray($swfList, $perPage);

        $paginated->appends([
            'q' => $searchQuery,
            'per_page' => $perPage,
            'sort' => $sortColumn,
            'sort_direction' => $sortDirection,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed SWF list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $searchQuery,
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => count($swfList),
            ],
        ]);

        return view('admin.swf.index', [
            'swf' => $paginated,
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

            return redirect()->route('admin.swf.index');
        }

        $swf = $this->googleSheetRepository->getMemberSwf($memberNumber);

        $totalContribution = (float) ($swf['total_contribution'] ?? 0);
        $benefits = (float) ($swf['benefits'] ?? 0);
        $currentBalance = (float) ($swf['current_balance'] ?? 0);
        $contributionHistory = $swf['contribution_history'] ?? [];

        $benefitsSummary = [];
        if ($benefits > 0) {
            $benefitsSummary[] = [
                'type' => 'Emergency Assistance',
                'amount' => $benefits * 0.6,
                'date' => '2024-03-15',
                'status' => 'Paid',
            ];
            $benefitsSummary[] = [
                'type' => 'Education Bursary',
                'amount' => $benefits * 0.4,
                'date' => '2024-01-10',
                'status' => 'Paid',
            ];
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'swf',
            'subject_id' => $memberNumber,
            'description' => "Admin viewed member SWF: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_name' => $member['name'] ?? null,
                'current_balance' => $currentBalance,
            ],
        ]);

        return view('admin.swf.show', [
            'member' => $member,
            'memberNumber' => $memberNumber,
            'swf' => $swf,
            'totalContribution' => $totalContribution,
            'benefits' => $benefits,
            'currentBalance' => $currentBalance,
            'contributionHistory' => $contributionHistory,
            'benefitsSummary' => $benefitsSummary,
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
