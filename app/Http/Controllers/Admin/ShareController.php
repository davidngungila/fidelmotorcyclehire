<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\AdminDashboardService;
use App\Services\EncryptedIdService;
use App\Services\MemberService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ShareController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $googleSheetRepository,
        protected MemberService $memberService,
        protected AdminDashboardService $dashboardService,
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request)
    {
        Gate::authorize('admin-only');

        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'member_number');
        $sortDirection = $request->input('sort_direction', 'asc');
        $searchQuery = $request->input('q', '');

        $allMembers = $this->googleSheetRepository->getAllMembers();

        $sharesList = [];
        foreach ($allMembers as $member) {
            $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $shares = $this->googleSheetRepository->getMemberShares($memberNo);
            foreach ($shares as $share) {
                $share['member_number'] = $memberNo;
                $share['member_name'] = $member['name'] ?? ($member['Name'] ?? 'Unknown');
                $share['member_branch'] = $member['branch'] ?? ($member['Branch'] ?? '-');
                $sharesList[] = $share;
            }
        }

        if (! empty($searchQuery)) {
            $sharesList = array_values(array_filter($sharesList, static function ($s) use ($searchQuery): bool {
                $query = strtolower(trim($searchQuery));
                $haystack = strtolower(implode(' ', [
                    $s['share_number'] ?? '',
                    $s['member_number'] ?? '',
                    $s['member_name'] ?? '',
                    $s['member_branch'] ?? '',
                ]));

                return str_contains($haystack, $query);
            }));
        }

        $sharesList = $this->memberService->sort($sharesList, $sortColumn, $sortDirection);
        $paginated = $this->memberService->paginateArray($sharesList, $perPage);

        $paginated->appends([
            'q' => $searchQuery,
            'per_page' => $perPage,
            'sort' => $sortColumn,
            'sort_direction' => $sortDirection,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed shares list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $searchQuery,
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => count($sharesList),
            ],
        ]);

        return view('admin.shares.index', [
            'shares' => $paginated,
            'searchQuery' => $searchQuery,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, string $encryptedMemberNumber)
    {
        $memberNumber = $this->encryptedIdService->decrypt($encryptedMemberNumber);
        
        Gate::authorize('admin-only');

        $member = $this->googleSheetRepository->getMemberByNumber($memberNumber);

        if (! $member) {
            $this->error("Member {$memberNumber} not found.");

            return redirect()->route('admin.shares.index');
        }

        $shares = $this->googleSheetRepository->getMemberShares($memberNumber);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'share',
            'subject_id' => null,
            'description' => "Admin viewed shares for member: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_number' => $memberNumber,
                'member_name' => $member['name'] ?? null,
                'total_shares' => count($shares),
            ],
        ]);

        return view('admin.shares.show', [
            'member' => $member,
            'memberNumber' => $memberNumber,
            'shares' => $shares,
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
