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

class SavingPlanController extends Controller
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
        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');
        $searchQuery = $request->input('q', '');

        $allPlans = $this->googleSheetRepository->getAllSavingPlans();
        $allMembers = $this->googleSheetRepository->getAllMembers();

        // Filter by search query
        if ($searchQuery !== '') {
            $searchQueryLower = strtolower($searchQuery);
            $allPlans = array_filter($allPlans, function ($plan) use ($searchQueryLower) {
                $name = strtolower((string) ($plan['name'] ?? ''));
                $memberId = strtolower((string) ($plan['memberid'] ?? ''));
                $membership = strtolower((string) ($plan['membership'] ?? ''));
                
                return str_contains($name, $searchQueryLower) ||
                       str_contains($memberId, $searchQueryLower) ||
                       str_contains($membership, $searchQueryLower);
            });
        }

        // Sort plans
        usort($allPlans, function ($a, $b) use ($sortColumn, $sortDirection) {
            $aVal = $a[$sortColumn] ?? '';
            $bVal = $b[$sortColumn] ?? '';
            
            if ($sortColumn === 'monthly_goal' || $sortColumn === 'goal') {
                $aVal = (float) $aVal;
                $bVal = (float) $bVal;
            }
            
            $cmp = $aVal <=> $bVal;
            return $sortDirection === 'asc' ? $cmp : -$cmp;
        });

        // Add member names to plans
        $memberMap = [];
        foreach ($allMembers as $member) {
            $memberNo = strtoupper($member['member_number'] ?? $member['MemberNumber'] ?? '');
            if ($memberNo) {
                $memberMap[$memberNo] = $member['name'] ?? $member['Name'] ?? 'Unknown';
            }
        }

        foreach ($allPlans as &$plan) {
            $memberId = strtoupper($plan['memberid'] ?? '');
            $plan['member_name'] = $memberMap[$memberId] ?? 'Unknown';
        }

        // Paginate
        $currentPage = (int) $request->input('page', 1);
        $total = count($allPlans);
        $plans = array_slice($allPlans, ($currentPage - 1) * $perPage, $perPage);

        return view('admin.saving-plans.index', [
            'plans' => $plans,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'searchQuery' => $searchQuery,
        ]);
    }

    public function show(Request $request, string $encryptedId)
    {
        $memberId = $this->encryptedIdService->decrypt($encryptedId);
        
        if (! Gate::allows('admin')) {
            abort(403);
        }

        $plans = $this->googleSheetRepository->getMemberSavingPlans($memberId);
        $member = $this->googleSheetRepository->getMemberByNumber($memberId);

        if (! $member) {
            $this->error('Member not found');
            return redirect()->route('admin.saving-plans.index');
        }

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'view',
            'subject' => 'saving_plans',
            'subject_id' => $memberId,
            'description' => "Viewed saving plans for member {$memberId}",
        ]);

        return view('admin.saving-plans.show', [
            'plans' => $plans,
            'member' => $member,
            'memberId' => $memberId,
        ]);
    }
}
