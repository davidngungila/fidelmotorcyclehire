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
        protected GoogleSheetRepositoryInterface $googleSheetRepository,
        protected MemberService $memberService,
        protected AdminDashboardService $dashboardService,
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'loan_number');
        $sortDirection = $request->input('sort_direction', 'asc');
        $statusFilter = $request->input('status', '');
        $searchQuery = $request->input('q', '');

        $allMembers = $this->googleSheetRepository->getAllMembers();
        $memberMap = [];
        foreach ($allMembers as $m) {
            $memberNo = $m['member_number'] ?? ($m['MemberNumber'] ?? null);
            if ($memberNo) {
                $memberMap[$memberNo] = $m;
            }
        }

        $loans = [];
        foreach ($allMembers as $member) {
            $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $memberLoans = $this->googleSheetRepository->getMemberLoans($memberNo);
            foreach ($memberLoans as $loan) {
                $loan['member_name'] = $member['name'] ?? ($member['Name'] ?? 'Unknown');
                $loan['member_number'] = $memberNo;
                $loan['member_phone'] = $member['phone'] ?? ($member['Phone'] ?? '-');
                $loan['member_branch'] = $member['branch'] ?? ($member['Branch'] ?? '-');
                $loans[] = $loan;
            }
        }

        // Merge with database loans information
        try {
            $dbLoans = \App\Models\LoanInformation::all();
            foreach ($dbLoans as $dbLoan) {
                $memberNo = $dbLoan->customer_id;
                if (isset($memberMap[$memberNo])) {
                    $member = $memberMap[$memberNo];
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
                        'member_name' => $member['name'] ?? ($member['Name'] ?? 'Unknown'),
                        'member_number' => $memberNo,
                        'member_phone' => $member['phone'] ?? ($member['Phone'] ?? '-'),
                        'member_branch' => $member['branch'] ?? ($member['Branch'] ?? '-'),
                        'interest_rate' => (float) $dbLoan->interest_rate_pm,
                        'duration' => $dbLoan->duration_months,
                        'total_payable' => (float) $dbLoan->total_payable,
                        'number_of_paid_installments' => $dbLoan->number_of_paid_installments,
                        'number_of_unpaid_installments' => $dbLoan->number_of_unpaid_installments,
                        'source' => 'database',
                    ];
                    $loans[] = $loan;
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Table doesn't exist yet, skip database loans
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

        $allMembers = $this->googleSheetRepository->getAllMembers();
        $loan = null;
        $member = null;

        foreach ($allMembers as $m) {
            $memberNo = $m['member_number'] ?? ($m['MemberNumber'] ?? null);
            if (! $memberNo) {
                continue;
            }
            $memberLoans = $this->googleSheetRepository->getMemberLoans($memberNo);
            foreach ($memberLoans as $l) {
                $currentLoanNo = $l['loan_number'] ?? ($l['LoanNumber'] ?? null);
                if ($currentLoanNo === $loanNumber) {
                    $loan = $l;
                    $member = $m;
                    break 2;
                }
            }
        }

        if (! $loan) {
            $this->error("Loan {$loanNumber} not found.");

            return redirect()->route('admin.loans.index');
        }

        $loanAmount = (float) ($loan['loan_amount'] ?? ($loan['LoanAmount'] ?? 0));
        $paidAmount = (float) ($loan['paid_amount'] ?? ($loan['PaidAmount'] ?? 0));
        $outstanding = (float) ($loan['outstanding_balance'] ?? ($loan['OutstandingBalance'] ?? 0));
        $progress = $loanAmount > 0 ? min(($paidAmount / $loanAmount) * 100, 100) : 0;

        $installment = (float) ($loan['installment'] ?? ($loan['Installment'] ?? 0));
        $interestRate = (float) ($loan['interest_rate'] ?? ($loan['InterestRate'] ?? 0));
        $disbursementDate = $loan['disbursement_date'] ?? ($loan['DisbursementDate'] ?? '-');
        $maturityDate = $loan['maturity_date'] ?? ($loan['MaturityDate'] ?? '-');

        $repaymentSchedule = [];
        $months = 0;
        if ($installment > 0 && $loanAmount > 0) {
            $months = (int) ceil($loanAmount / $installment);
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
                    'description' => "Loan disbursed - {$loan['loan_product']}",
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
                'member_number' => $member['member_number'] ?? ($member['MemberNumber'] ?? null),
                'member_name' => $member['name'] ?? ($member['Name'] ?? null),
                'loan_product' => $loan['loan_product'] ?? null,
            ],
        ]);

        return view('admin.loans.show', [
            'loan' => $loan,
            'loanNumber' => $loanNumber,
            'member' => $member,
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
