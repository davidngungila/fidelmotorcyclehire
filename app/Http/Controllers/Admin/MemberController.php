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
use App\Services\EncryptedIdService;
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
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'member_number');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Get members from Google Sheets
        if ($request->filled('q')) {
            $sheetMembers = $this->googleSheetRepository->searchMembers($request->input('q'));
        } else {
            $sheetMembers = $this->googleSheetRepository->getAllMembers();
        }

        // Get members from database (imported members)
        $dbMembersQuery = \App\Models\Member::query();
        
        // Apply search filter to database members
        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            $dbMembersQuery->where(function($query) use ($searchTerm) {
                $query->where('member_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('full_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $dbMembers = $dbMembersQuery->get()->map(function($member) {
            return [
                'member_number' => $member->member_number,
                'name' => $member->full_name,
                'gender' => $member->gender,
                'phone' => $member->phone,
                'email' => $member->email,
                'branch' => $member->branch ?? '-',
                'status' => $member->status,
                'photo' => $member->photo,
            ];
        })->toArray();

        // Merge members, prioritizing database members
        $membersMap = [];
        foreach ($dbMembers as $member) {
            $membersMap[$member['member_number']] = $member;
        }
        foreach ($sheetMembers as $member) {
            $memberNo = $member['member_number'] ?? $member['MemberNumber'] ?? null;
            if ($memberNo && !isset($membersMap[$memberNo])) {
                $membersMap[$memberNo] = $member;
            }
        }
        $members = array_values($membersMap);

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
        // Check if the member number is encrypted or plain
        $isEncrypted = strlen($memberNumber) > 10; // Encrypted IDs are typically longer
        
        if ($isEncrypted) {
            try {
                $memberNumber = $this->encryptedIdService->decrypt($memberNumber);
            } catch (\Exception $e) {
                // If decryption fails, treat as plain member number
                $memberNumber = $request->route('memberNumber');
            }
        }
        
        Gate::authorize('view-member-data', $memberNumber);

        // First check database for imported members
        $dbMember = \App\Models\Member::where('member_number', $memberNumber)->first();
        
        if ($dbMember) {
            $member = [
                'member_number' => $dbMember->member_number,
                'name' => $dbMember->full_name,
                'gender' => $dbMember->gender,
                'phone' => $dbMember->phone,
                'email' => $dbMember->email,
                'branch' => $dbMember->branch ?? '-',
                'status' => $dbMember->status,
                'registration_date' => $dbMember->registration_date,
                'date_of_birth' => $dbMember->date_of_birth,
                'national_id' => $dbMember->national_id,
                'occupation' => $dbMember->occupation,
                'employer' => $dbMember->employer,
                'residential_address' => $dbMember->residential_address,
                'member_type' => $dbMember->member_type,
                'marital_status' => $dbMember->marital_status,
                'bank_name' => $dbMember->bank_name,
                'bank_branch' => $dbMember->bank_branch,
                'account_name' => $dbMember->account_name,
                'account_number' => $dbMember->account_number,
                'bank_account_status' => $dbMember->bank_account_status,
                'mobile_money_provider' => $dbMember->mobile_money_provider,
                'mobile_money_number' => $dbMember->mobile_money_number,
                'emergency_contact_name' => $dbMember->emergency_contact_name,
                'emergency_contact_phone' => $dbMember->emergency_contact_phone,
                'emergency_contact_relationship' => $dbMember->emergency_contact_relationship,
                'registration_fee' => $dbMember->registration_fee,
                'notes' => $dbMember->notes,
                'photo' => $dbMember->photo,
            ];
            
            // For imported members, try to get data from Google Sheets if available
            $loans = $this->googleSheetRepository->getMemberLoans($memberNumber);
            $savings = $this->googleSheetRepository->getMemberSavings($memberNumber);
            $deposits = $this->googleSheetRepository->getMemberDeposits($memberNumber);
            $swf = $this->googleSheetRepository->getMemberSwf($memberNumber);
            $investments = $this->googleSheetRepository->getMemberInvestments($memberNumber);
        } else {
            // Fall back to Google Sheets
            $member = $this->googleSheetRepository->getMemberByNumber($memberNumber);

            if (! $member) {
                $this->error("Member {$memberNumber} not found.");

                return redirect()->route('admin.members.index');
            }

            // Add photo field if available in Google Sheets data
            if (isset($member['photo'])) {
                $member['photo'] = $member['photo'];
            } elseif (isset($member['Photo'])) {
                $member['photo'] = $member['Photo'];
            }

            $loans = $this->googleSheetRepository->getMemberLoans($memberNumber);
            $savings = $this->googleSheetRepository->getMemberSavings($memberNumber);
            $deposits = $this->googleSheetRepository->getMemberDeposits($memberNumber);
            $swf = $this->googleSheetRepository->getMemberSwf($memberNumber);
            $investments = $this->googleSheetRepository->getMemberInvestments($memberNumber);
        }

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
            'encryptedMemberNumber' => $this->encryptedIdService->encrypt($memberNumber),
            'loans' => $loans,
            'savings' => $savings,
            'deposits' => $deposits,
            'swf' => $swf,
            'investments' => $investments,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function loans(Request $request, string $encryptedMemberNumber)
    {
        $memberNumber = $this->encryptedIdService->decrypt($encryptedMemberNumber);
        
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
            'encryptedMemberNumber' => $encryptedMemberNumber,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function savings(Request $request, string $encryptedMemberNumber)
    {
        $memberNumber = $this->encryptedIdService->decrypt($encryptedMemberNumber);
        
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
            'encryptedMemberNumber' => $encryptedMemberNumber,
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
            // Increase execution time for large files
            set_time_limit(300);
            
            $jobId = Str::uuid()->toString();
            $file = $request->file('file');
            
            // Process import directly from uploaded file without storing
            $googleSheetRepository = $this->googleSheetRepository;
            $import = new \App\Imports\MembersImport($googleSheetRepository);
            
            Excel::import($import, $file);

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            $createdUsers = $import->getCreatedUsers();

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Admin imported members from Excel',
                'properties' => [
                    'imported_count' => $importedCount,
                    'created_users_count' => count($createdUsers),
                    'created_users' => $createdUsers,
                    'errors_count' => count($errors),
                    'errors' => $errors,
                ],
            ]);

            return response()->json([
                'success' => true,
                'job_id' => $jobId,
                'message' => 'Import completed successfully',
                'imported' => $importedCount,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Import failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import: ' . $e->getMessage(),
                'error_type' => get_class($e),
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
