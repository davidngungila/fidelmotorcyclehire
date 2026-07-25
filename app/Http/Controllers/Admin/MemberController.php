<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Exports\MembersTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\MembersImport;
use App\Jobs\ImportMembersJob;
use App\Models\ActivityLog;
use App\Services\AdminDashboardService;
use App\Services\MemberService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
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
            $jobId = Str::uuid()->toString();
            $file = $request->file('file');
            $filePath = $file->storeAs('temp', 'import_' . $jobId . '.' . $file->getClientOriginalExtension());

            ImportMembersJob::dispatch($filePath, $this->googleSheetRepository, Auth::id(), $jobId);

            return response()->json([
                'success' => true,
                'job_id' => $jobId,
                'message' => 'Import started successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start import: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importProgress($jobId)
    {
        Gate::authorize('admin-only');

        $progress = Cache::get("import_{$jobId}", [
            'status' => 'pending',
            'progress' => 0,
            'message' => 'Waiting to start...',
            'imported' => 0,
            'total' => 0,
        ]);

        return response()->json($progress);
    }

    public function downloadTemplate()
    {
        Gate::authorize('admin-only');

        return Excel::download(new MembersTemplateExport, 'members_import_template.xlsx');
    }
}
