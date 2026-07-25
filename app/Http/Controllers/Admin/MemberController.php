<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Imports\MembersImport;
use App\Models\ActivityLog;
use App\Services\AdminDashboardService;
use App\Services\MemberService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
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

        if ($request->filled('q')) {
            $members = $this->googleSheetRepository->searchMembers($request->input('q'));
        } else {
            $members = $this->googleSheetRepository->getAllMembers();
        }

        $members = $this->memberService->sort($members, $sortColumn, $sortDirection);
        $chunked = $this->memberService->chunkArray($members, $perPage);
        $paginated = $this->memberService->paginateArray($members, $perPage);

        $paginated->appends([
            'q' => $request->input('q'),
            'per_page' => $perPage,
            'sort' => $sortColumn,
            'sort_direction' => $sortDirection,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed members list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $request->input('q'),
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => count($members),
            ],
        ]);

        return view('admin.members.index', [
            'members' => $paginated,
            'searchQuery' => $request->input('q'),
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, string $memberNumber)
    {
        Gate::authorize('view-member-data', $memberNumber);

        $member = $this->googleSheetRepository->getMemberByNumber($memberNumber);

        if (! $member) {
            $this->error("Member {$memberNumber} not found.");

            return redirect()->route('admin.members.index');
        }

        $loans = $this->googleSheetRepository->getMemberLoans($memberNumber);
        $savings = $this->googleSheetRepository->getMemberSavings($memberNumber);
        $deposits = $this->googleSheetRepository->getMemberDeposits($memberNumber);
        $swf = $this->googleSheetRepository->getMemberSwf($memberNumber);
        $investments = $this->googleSheetRepository->getMemberInvestments($memberNumber);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'member',
            'subject_id' => null,
            'description' => "Admin viewed member profile: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_name' => $member['name'] ?? null,
            ],
        ]);

        return view('admin.members.show', [
            'member' => $member,
            'memberNumber' => $memberNumber,
            'loans' => $loans,
            'savings' => $savings,
            'deposits' => $deposits,
            'swf' => $swf,
            'investments' => $investments,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function loans(Request $request, string $memberNumber)
    {
        Gate::authorize('view-member-data', $memberNumber);

        $member = $this->googleSheetRepository->getMemberByNumber($memberNumber);
        $loans = $this->googleSheetRepository->getMemberLoans($memberNumber);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'member',
            'subject_id' => null,
            'description' => "Admin viewed member loans: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'member' => $member,
                'loans' => $loans,
            ]);
        }

        return view('admin.members.partials.loans', [
            'member' => $member,
            'loans' => $loans,
            'memberNumber' => $memberNumber,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function savings(Request $request, string $memberNumber)
    {
        Gate::authorize('view-member-data', $memberNumber);

        $member = $this->googleSheetRepository->getMemberByNumber($memberNumber);
        $savings = $this->googleSheetRepository->getMemberSavings($memberNumber);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'member',
            'subject_id' => null,
            'description' => "Admin viewed member savings: {$memberNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'member' => $member,
                'savings' => $savings,
            ]);
        }

        return view('admin.members.partials.savings', [
            'member' => $member,
            'savings' => $savings,
            'memberNumber' => $memberNumber,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function import(Request $request)
    {
        Gate::authorize('admin-only');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new MembersImport($this->googleSheetRepository);
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Admin imported members from Excel',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => [
                    'imported_count' => $importedCount,
                    'errors_count' => count($errors),
                    'errors' => $errors,
                ],
            ]);

            if ($importedCount > 0) {
                $this->success("Successfully imported {$importedCount} member(s).");
                if (!empty($errors)) {
                    $this->warning(count($errors) . ' row(s) were skipped due to errors.');
                }
            } else {
                $this->error('No members were imported. Please check your file format.');
            }

            return redirect()->route('admin.members.index');
        } catch (\Exception $e) {
            $this->error('Failed to import members: ' . $e->getMessage());
            return redirect()->route('admin.members.index');
        }
    }
}
