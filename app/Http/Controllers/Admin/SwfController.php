<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SwfMember;
use App\Services\AdminDashboardService;
use App\Services\EncryptedIdService;
use App\Services\MemberService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SwfController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected MemberService $memberService,
        protected AdminDashboardService $dashboardService,
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'membership_number');
        $sortDirection = $request->input('sort_direction', 'asc');
        $searchQuery = $request->input('q', '');

        $query = SwfMember::with(['user', 'contributions', 'benefits']);

        if (!empty($searchQuery)) {
            $query->whereHas('user', function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('email', 'like', "%{$searchQuery}%")
                  ->orWhere('member_number', 'like', "%{$searchQuery}%");
            })->orWhere('membership_number', 'like', "%{$searchQuery}%");
        }

        $swfMembers = $query->orderBy($sortColumn, $sortDirection)->paginate($perPage);

        $swfMembers->appends([
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
                'total_count' => $swfMembers->total(),
            ],
        ]);

        return view('admin.swf.index', [
            'swf' => $swfMembers,
            'searchQuery' => $searchQuery,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, string $id): View
    {
        Gate::authorize('admin-only');

        $swfMember = SwfMember::with(['user', 'contributions', 'benefits'])->findOrFail($id);

        $totalContribution = $swfMember->total_contributions;
        $benefits = $swfMember->total_benefits_received;
        $currentBalance = $swfMember->total_contributions - $swfMember->total_benefits_received;
        $contributionHistory = $swfMember->contributions->map(function ($contribution) {
            return [
                'date' => $contribution->contribution_date->format('Y-m-d'),
                'amount' => $contribution->amount,
                'payment_method' => $contribution->payment_method,
                'reference_number' => $contribution->reference_number,
            ];
        })->toArray();

        $benefitsSummary = $swfMember->benefits->map(function ($benefit) {
            return [
                'type' => $benefit->name,
                'amount' => $benefit->pivot->amount,
                'date' => $benefit->pivot->received_date,
                'status' => $benefit->pivot->status,
            ];
        })->toArray();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'swf_member',
            'subject_id' => $swfMember->id,
            'description' => "Admin viewed SWF member: {$swfMember->membership_number}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'membership_number' => $swfMember->membership_number,
                'user_name' => $swfMember->user->name,
                'current_balance' => $currentBalance,
            ],
        ]);

        return view('admin.swf.show', [
            'member' => $swfMember->user,
            'memberNumber' => $swfMember->membership_number,
            'swf' => [
                'total_contribution' => $totalContribution,
                'benefits' => $benefits,
                'current_balance' => $currentBalance,
                'contribution_history' => $contributionHistory,
                'enrollment_date' => $swfMember->join_date,
            ],
            'totalContribution' => $totalContribution,
            'benefits' => $benefits,
            'currentBalance' => $currentBalance,
            'contributionHistory' => $contributionHistory,
            'benefitsSummary' => $benefitsSummary,
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
