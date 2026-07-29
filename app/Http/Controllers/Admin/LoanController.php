<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Loan;
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

        $query = Loan::with('user')->pending();

        $loans = $query->orderBy('application_date', 'desc')->paginate(15);

        return view('admin.loans.applications', [
            'loans' => $loans,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function repayments(Request $request)
    {
        Gate::authorize('admin-only');

        $query = Loan::with('user')->active();

        $loans = $query->orderBy('disbursement_date', 'desc')->paginate(15);

        return view('admin.loans.repayments', [
            'loans' => $loans,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function index(Request $request)
    {
        Gate::authorize('admin-only');

        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'application_date');
        $sortDirection = $request->input('sort_direction', 'desc');
        $statusFilter = $request->input('status', '');
        $searchQuery = $request->input('q', '');

        $query = Loan::with('user');

        // Search
        if (!empty($searchQuery)) {
            $query->where('loan_number', 'like', '%' . $searchQuery . '%')
                  ->orWhere('member_number', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('user', function ($q) use ($searchQuery) {
                      $q->where('name', 'like', '%' . $searchQuery . '%');
                  });
        }

        // Filter by status
        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        // Sort
        $query->orderBy($sortColumn, $sortDirection);

        $loans = $query->paginate($perPage);

        $loans->appends([
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
                'total_count' => $loans->total(),
            ],
        ]);

        return view('admin.loans.index', [
            'loans' => $loans,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'memberService' => $this->memberService,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function create(Request $request)
    {
        Gate::authorize('admin-only');

        return view('admin.loans.create', [
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'member_number' => 'required|string|max:50',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'term_months' => 'required|integer|min:1',
            'application_date' => 'required|date',
            'purpose' => 'required|in:business,education,agriculture,personal,emergency,other',
            'purpose_description' => 'nullable|string',
            'collateral' => 'nullable|string',
            'guarantor' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Generate loan number
        $validated['loan_number'] = 'LOAN-' . date('Ymd') . '-' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'pending';

        Loan::create($validated);

        $this->success('Loan application created successfully.');

        return redirect()->route('admin.loans.index');
    }

    public function show(Request $request, string $encryptedLoanNumber)
    {
        try {
            $loanNumber = $this->encryptedIdService->decrypt($encryptedLoanNumber);
        } catch (\Exception $e) {
            $this->error('Invalid loan number.');
            return redirect()->route('admin.loans.index');
        }
        
        Gate::authorize('admin-only');

        $loan = Loan::with('user')->where('loan_number', $loanNumber)->first();

        if (!$loan) {
            $this->error("Loan {$loanNumber} not found.");
            return redirect()->route('admin.loans.index');
        }

        $loanAmount = (float) $loan->principal_amount;
        $paidAmount = (float) $loan->amount_paid;
        $outstanding = (float) $loan->balance;
        $progress = $loanAmount > 0 ? min(($paidAmount / $loanAmount) * 100, 100) : 0;

        $installment = (float) $loan->monthly_payment;
        $interestRate = (float) $loan->interest_rate;
        $disbursementDate = $loan->disbursement_date ? $loan->disbursement_date->format('Y-m-d') : '-';
        $maturityDate = $loan->maturity_date ? $loan->maturity_date->format('Y-m-d') : '-';

        $repaymentSchedule = [];
        $months = $loan->term_months;
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
        if ($paidAmount > 0 && !empty($repaymentSchedule)) {
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
                    'description' => "Loan disbursed",
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
            'subject_id' => $loan->id,
            'description' => "Admin viewed loan {$loanNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'member_number' => $loan->member_number,
                'member_name' => $loan->user->name ?? 'Unknown',
                'loan_amount' => $loanAmount,
            ],
        ]);

        return view('admin.loans.show', [
            'loan' => [
                'loan_number' => $loan->loan_number,
                'loan_product' => ucfirst($loan->purpose),
                'loan_amount' => $loanAmount,
                'outstanding_balance' => $outstanding,
                'paid_amount' => $paidAmount,
                'installment' => $installment,
                'status' => $loan->status,
                'maturity_date' => $maturityDate,
                'disbursement_date' => $disbursementDate,
            ],
            'loanNumber' => $loanNumber,
            'member' => [
                'name' => $loan->user->name ?? 'Unknown',
                'member_number' => $loan->member_number,
                'phone' => $loan->user->phone ?? '-',
                'branch' => '-',
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

    public function edit(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $loan = Loan::findOrFail($id);

        return view('admin.loans.edit', [
            'loan' => $loan,
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'member_number' => 'required|string|max:50',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'term_months' => 'required|integer|min:1',
            'application_date' => 'required|date',
            'approval_date' => 'nullable|date',
            'disbursement_date' => 'nullable|date',
            'maturity_date' => 'nullable|date',
            'monthly_payment' => 'nullable|numeric|min:0',
            'total_amount_due' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'balance' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,disbursed,active,paid,defaulted,rejected',
            'purpose' => 'required|in:business,education,agriculture,personal,emergency,other',
            'purpose_description' => 'nullable|string',
            'collateral' => 'nullable|string',
            'guarantor' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $loan->update($validated);

        $this->success('Loan updated successfully.');

        return redirect()->route('admin.loans.index');
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $loan = Loan::findOrFail($id);
        $loan->delete();

        $this->success('Loan deleted successfully.');

        return redirect()->route('admin.loans.index');
    }

    public function approve(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $loan = Loan::findOrFail($id);
        $loan->update([
            'status' => 'approved',
            'approval_date' => now(),
        ]);

        $this->success('Loan approved successfully.');

        return redirect()->back();
    }

    public function disburse(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $loan = Loan::findOrFail($id);
        
        $validated = $request->validate([
            'disbursement_date' => 'required|date',
            'maturity_date' => 'required|date',
            'monthly_payment' => 'required|numeric|min:0',
            'total_amount_due' => 'required|numeric|min:0',
        ]);

        $loan->update([
            'status' => 'disbursed',
            'disbursement_date' => $validated['disbursement_date'],
            'maturity_date' => $validated['maturity_date'],
            'monthly_payment' => $validated['monthly_payment'],
            'total_amount_due' => $validated['total_amount_due'],
            'balance' => $validated['total_amount_due'],
        ]);

        $this->success('Loan disbursed successfully.');

        return redirect()->back();
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
