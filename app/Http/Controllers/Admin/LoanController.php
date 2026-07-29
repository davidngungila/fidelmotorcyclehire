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

class LoanController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected MemberService $memberService,
        protected AdminDashboardService $dashboardService,
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function applications(Request $request)
    {
        Gate::authorize('admin-only');

        return view('admin.loans.applications', [
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function repayments(Request $request)
    {
        Gate::authorize('admin-only');

        return view('admin.loans.repayments', [
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'loan_number');
        $sortDirection = $request->input('sort_direction', 'asc');
        $statusFilter = $request->input('status', '');
        $searchQuery = $request->input('q', '');

        // Get all loans from database
        $loans = [];
        try {
            $dbLoans = \App\Models\LoanInformation::all();
            foreach ($dbLoans as $dbLoan) {
                // Get user information
                $user = null;
                if ($dbLoan->user_id) {
                    $user = \App\Models\User::find($dbLoan->user_id);
                }
                
                // Fallback to customer_id if user not found
                if (!$user && $dbLoan->customer_id) {
                    $user = \App\Models\User::where('member_number', $dbLoan->customer_id)->first();
                }
                
                $memberName = $user ? $user->name : 'Unknown';
                $memberNo = $user ? $user->member_number : ($dbLoan->customer_id ?? 'Unknown');
                $memberPhone = $user ? $user->phone : '-';
                $memberBranch = '-';
                
                $loan = [
                    'loan_number' => $dbLoan->loan_id,
                    'loan_product' => $dbLoan->loan_type,
                    'loan_amount' => (float) $dbLoan->loan_amount,
                    'outstanding_balance' => (float) $dbLoan->outstanding_balance,
                    'paid_amount' => (float) $dbLoan->total_paid,
                    'installment' => (float) $dbLoan->monthly_installment,
                    'status' => $dbLoan->loan_status,
                    'maturity_date' => $dbLoan->loan_maturity_date ? $dbLoan->loan_maturity_date->format('Y-m-d') : null,
                    'disbursement_date' => $dbLoan->loan_start_date ? $dbLoan->loan_start_date->format('Y-m-d') : null,
                    'member_name' => $memberName,
                    'member_number' => $memberNo,
                    'member_phone' => $memberPhone,
                    'member_branch' => $memberBranch,
                    'interest_rate' => (float) $dbLoan->interest_rate_pm,
                    'duration' => $dbLoan->duration_months,
                    'total_payable' => (float) $dbLoan->total_payable,
                    'number_of_paid_installments' => $dbLoan->number_of_paid_installments,
                    'number_of_unpaid_installments' => $dbLoan->number_of_unpaid_installments,
                    'source' => 'database',
                    'encrypted_id' => $this->encryptedIdService->encrypt($dbLoan->loan_id),
                    'user_id' => $dbLoan->user_id,
                ];
                $loans[] = $loan;
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Table doesn't exist yet, return empty array
            $loans = [];
        }

        if (! empty($searchQuery)) {
            $loans = array_values(array_filter($loans, static function ($loan) use ($searchQuery): bool {
                $query = strtolower(trim($searchQuery));
                $haystack = strtolower(implode(' ', [
                    $loan['loan_number'] ?? '',
                    $loan['loan_product'] ?? '',
                    $loan['member_number'] ?? '',
                    $loan['member_name'] ?? '',
                    $loan['member_phone'] ?? '',
                ]));

                return str_contains($haystack, $query);
            }));
        }

        if (! empty($statusFilter)) {
            $loans = $this->memberService->filterByStatus($loans, $statusFilter);
        }

        $loans = $this->memberService->sort($loans, $sortColumn, $sortDirection);
        $paginated = $this->memberService->paginateArray($loans, $perPage);

        $paginated->appends([
            'q' => $searchQuery,
            'status' => $statusFilter,
            'per_page' => $perPage,
            'sort' => $sortColumn,
            'sort_direction' => $sortDirection,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed loans list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $searchQuery,
                'status_filter' => $statusFilter,
                'per_page' => $perPage,
                'sort' => $sortColumn,
                'sort_direction' => $sortDirection,
                'total_count' => count($loans),
            ],
        ]);

        return view('admin.loans.index', [
            'loans' => $paginated,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, string $encryptedLoanNumber)
    {
        $loanNumber = $this->encryptedIdService->decrypt($encryptedLoanNumber);
        
        Gate::authorize('admin-only');

        // Get loan from database
        $loan = \App\Models\LoanInformation::where('loan_id', $loanNumber)->first();
        
        if (! $loan) {
            $this->error("Loan {$loanNumber} not found.");
            return redirect()->route('admin.loans.index');
        }

        // Get user information
        $user = null;
        if ($loan->user_id) {
            $user = \App\Models\User::find($loan->user_id);
        }
        
        // Fallback to customer_id if user not found
        if (!$user && $loan->customer_id) {
            $user = \App\Models\User::where('member_number', $loan->customer_id)->first();
        }

        $memberName = $user ? $user->name : 'Unknown';
        $memberNo = $user ? $user->member_number : ($loan->customer_id ?? 'Unknown');
        $memberPhone = $user ? $user->phone : '-';
        $memberBranch = '-';

        $loanAmount = (float) $loan->loan_amount;
        $paidAmount = (float) $loan->total_paid;
        $outstanding = (float) $loan->outstanding_balance;
        $progress = $loanAmount > 0 ? min(($paidAmount / $loanAmount) * 100, 100) : 0;

        $installment = (float) $loan->monthly_installment;
        $interestRate = (float) $loan->interest_rate_pm;
        $disbursementDate = $loan->loan_start_date ? $loan->loan_start_date->format('Y-m-d') : '-';
        $maturityDate = $loan->loan_maturity_date ? $loan->loan_maturity_date->format('Y-m-d') : '-';

        $repaymentSchedule = [];
        $months = $loan->duration_months ?? 0;
        if ($installment > 0 && $loanAmount > 0 && $months > 0) {
            $balance = $loanAmount;
            $startDate = $disbursementDate !== '-' ? $disbursementDate : date('Y-m-01');
            for ($i = 1; $i <= $months; $i++) {
                $paymentDate = date('Y-m-d', strtotime("+{$i} month", strtotime($startDate)));
                $interestPortion = $balance * ($interestRate / 100 / 12);
                $principalPortion = $installment - $interestPortion;
                $balance = max(0, $balance - $principalPortion);
                $status = $paidAmount >= ($i * $installment) ? 'Paid' : 'Pending';
                $repaymentSchedule[] = [
                    'installment_no' => $i,
                    'due_date' => $paymentDate,
                    'amount' => $installment,
                    'principal' => $principalPortion,
                    'interest' => $interestPortion,
                    'balance_after' => $balance,
                    'status' => $status,
                ];
            }
        }

        $repaymentHistory = [];
        if ($paidAmount > 0 && ! empty($repaymentSchedule)) {
            $paidCount = (int) floor($paidAmount / $installment);
            $paidCount = min($paidCount, count($repaymentSchedule));
            for ($i = 0; $i < $paidCount; $i++) {
                $repaymentHistory[] = array_merge($repaymentSchedule[$i], [
                    'payment_date' => $repaymentSchedule[$i]['due_date'],
                    'transaction_ref' => 'PAY-' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
                    'method' => 'Bank Transfer',
                ]);
            }
            $remaining = $paidAmount - ($paidCount * $installment);
            if ($remaining > 0 && $paidCount < count($repaymentSchedule)) {
                $repaymentHistory[] = array_merge($repaymentSchedule[$paidCount], [
                    'amount' => $remaining,
                    'payment_date' => $repaymentSchedule[$paidCount]['due_date'],
                    'transaction_ref' => 'PAY-' . str_pad((string) ($paidCount + 1), 6, '0', STR_PAD_LEFT),
                    'method' => 'Partial Payment',
                ]);
            }
        }

        $loanStatement = array_merge(
            [
                [
                    'date' => $disbursementDate,
                    'type' => 'Disbursement',
                    'reference' => $loanNumber,
                    'debit' => 0,
                    'credit' => $loanAmount,
                    'balance' => $loanAmount,
                    'description' => "Loan disbursed - {$loan->loan_type}",
                ],
            ],
            array_map(static fn ($h) => [
                'date' => $h['payment_date'] ?? $h['due_date'],
                'type' => 'Repayment',
                'reference' => $h['transaction_ref'] ?? 'PAY-000000',
                'debit' => $h['amount'],
                'credit' => 0,
                'balance' => $h['balance_after'] ?? 0,
                'description' => $h['method'] ?? 'Loan Repayment',
            ], $repaymentHistory)
        );

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'loan',
            'subject_id' => null,
            'description' => "Admin viewed loan {$loanNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_number' => $memberNo,
                'member_name' => $memberName,
                'loan_product' => $loan->loan_type,
            ],
        ]);

        return view('admin.loans.show', [
            'loan' => [
                'loan_number' => $loan->loan_id,
                'loan_product' => $loan->loan_type,
                'loan_amount' => $loanAmount,
                'outstanding_balance' => $outstanding,
                'paid_amount' => $paidAmount,
                'installment' => $installment,
                'status' => $loan->loan_status,
                'maturity_date' => $maturityDate,
                'disbursement_date' => $disbursementDate,
            ],
            'loanNumber' => $loanNumber,
            'member' => [
                'name' => $memberName,
                'member_number' => $memberNo,
                'phone' => $memberPhone,
                'branch' => $memberBranch,
            ],
            'progress' => $progress,
            'loanAmount' => $loanAmount,
            'paidAmount' => $paidAmount,
            'outstanding' => $outstanding,
            'installment' => $installment,
            'interestRate' => $interestRate,
            'disbursementDate' => $disbursementDate,
            'maturityDate' => $maturityDate,
            'repaymentSchedule' => $repaymentSchedule,
            'repaymentHistory' => $repaymentHistory,
            'loanStatement' => $loanStatement,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function importLoanPayments(Request $request)
    {
        Gate::authorize('admin-only');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $import = new \App\Imports\LoanPaymentsImport();

        try {
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);
            
            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();

            $this->success("Loan payments imported successfully. Imported: {$importedCount} records.");

            ActivityLog::create([
                'user_id' => Auth::id(),
                'subject_type' => 'loan_payment',
                'subject_id' => null,
                'description' => 'Admin imported loan payments',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => [
                    'imported_count' => $importedCount,
                    'skipped_count' => $skippedCount,
                ],
            ]);

            return redirect()->back();
        } catch (\Exception $e) {
            $this->error('Error importing loan payments: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function importLoansInformation(Request $request)
    {
        Gate::authorize('admin-only');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $import = new \App\Imports\LoansInformationImport();

        try {
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);
            
            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();

            $this->success("Loans information imported successfully. Imported: {$importedCount} records.");

            ActivityLog::create([
                'user_id' => Auth::id(),
                'subject_type' => 'loans_information',
                'subject_id' => null,
                'description' => 'Admin imported loans information',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => [
                    'imported_count' => $importedCount,
                    'skipped_count' => $skippedCount,
                ],
            ]);

            return redirect()->back();
        } catch (\Exception $e) {
            $this->error('Error importing loans information: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
