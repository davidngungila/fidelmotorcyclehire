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
            'loan_product_id' => 'nullable|exists:loan_products,id',
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

        $loan = Loan::create($validated);

        // Create repayment schedule
        $this->createRepaymentSchedule($loan);

        $this->success('Loan application created successfully.');

        return redirect()->route('admin.loans.index');
    }

    private function createRepaymentSchedule(Loan $loan)
    {
        $principal = (float) $loan->principal_amount;
        $interestRate = (float) $loan->interest_rate;
        $termMonths = (int) $loan->term_months;
        
        // Calculate monthly payment using amortization formula
        if ($interestRate > 0) {
            $monthlyRate = $interestRate / 100 / 12;
            $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / (pow(1 + $monthlyRate, $termMonths) - 1);
        } else {
            $monthlyPayment = $principal / $termMonths;
        }

        $balance = $principal;
        $startDate = $loan->application_date ? $loan->application_date->format('Y-m-d') : date('Y-m-d');

        for ($i = 1; $i <= $termMonths; $i++) {
            $dueDate = date('Y-m-d', strtotime("+{$i} month", strtotime($startDate)));
            
            $interestPortion = $balance * ($interestRate / 100 / 12);
            $principalPortion = $monthlyPayment - $interestPortion;
            $balance = max(0, $balance - $principalPortion);

            \App\Models\LoanRepaymentSchedule::create([
                'loan_id' => $loan->id,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'principal_amount' => $principalPortion,
                'interest_amount' => $interestPortion,
                'total_amount' => $monthlyPayment,
                'balance_after' => $balance,
                'status' => 'pending',
                'amount_paid' => 0,
            ]);
        }

        // Update loan with calculated monthly payment
        $loan->update([
            'monthly_payment' => $monthlyPayment,
            'total_amount_due' => $monthlyPayment * $termMonths,
            'balance' => $principal,
        ]);
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

        $loan = Loan::with(['user', 'repaymentSchedules'])->where('loan_number', $loanNumber)->first();

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

        // Load repayment schedule from database
        $repaymentSchedule = $loan->repaymentSchedules->map(function ($schedule) {
            return [
                'installment_no' => $schedule->installment_number,
                'due_date' => $schedule->due_date->format('Y-m-d'),
                'amount' => (float) $schedule->total_amount,
                'principal' => (float) $schedule->principal_amount,
                'interest' => (float) $schedule->interest_amount,
                'balance_after' => (float) $schedule->balance_after,
                'status' => ucfirst($schedule->status),
            ];
        })->toArray();

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
                'id' => $loan->id,
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
            'disbursement_method' => 'required|in:bank_transfer,mobile_money,cash,cheque',
            'account_wallet' => 'required|string',
            'maturity_date' => 'required|date',
            'first_repayment_date' => 'nullable|date',
            'monthly_payment' => 'required|numeric|min:0',
            'total_amount_due' => 'required|numeric|min:0',
            'processing_fee' => 'nullable|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $processingFee = $validated['processing_fee'] ?? 0;
        $insuranceFee = $validated['insurance_fee'] ?? 0;
        $otherDeductions = $validated['other_deductions'] ?? 0;
        $totalDeductions = $processingFee + $insuranceFee + $otherDeductions;
        $netAmountPaid = $loan->principal_amount - $totalDeductions;

        // Create disbursement record
        \App\Models\LoanDisbursement::create([
            'disbursement_number' => 'LND-' . date('Y') . '-' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'loan_id' => $loan->id,
            'loan_number' => $loan->loan_number,
            'member_number' => $loan->member_number,
            'member_name' => $loan->user->name ?? 'Unknown',
            'loan_product' => $loan->loanProduct->name ?? 'Unknown',
            'approved_amount' => $loan->principal_amount,
            'disbursed_amount' => $loan->principal_amount,
            'disbursement_date' => $validated['disbursement_date'],
            'disbursement_method' => $validated['disbursement_method'],
            'account_wallet' => $validated['account_wallet'],
            'interest_rate' => $loan->interest_rate,
            'repayment_period' => $loan->term_months,
            'first_repayment_date' => $validated['first_repayment_date'] ?? null,
            'maturity_date' => $validated['maturity_date'],
            'processing_fee' => $processingFee,
            'insurance_fee' => $insuranceFee,
            'other_deductions' => $otherDeductions,
            'net_amount_paid' => $netAmountPaid,
            'disbursed_by' => Auth::id(),
            'approved_by' => $loan->approval_date ? Auth::id() : null,
            'status' => 'disbursed',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        $loan->update([
            'status' => 'disbursed',
            'disbursement_date' => $validated['disbursement_date'],
            'maturity_date' => $validated['maturity_date'],
            'monthly_payment' => $validated['monthly_payment'],
            'total_amount_due' => $validated['total_amount_due'],
            'balance' => $validated['total_amount_due'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin disbursed loan: ' . $loan->loan_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Loan disbursed successfully.');

        return redirect()->back();
    }

    public function recordPayment(Request $request, string $encryptedLoanNumber)
    {
        try {
            $loanNumber = $this->encryptedIdService->decrypt($encryptedLoanNumber);
        } catch (\Exception $e) {
            $this->error('Invalid loan number.');
            return redirect()->route('admin.loans.index');
        }

        Gate::authorize('admin-only');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque',
            'notes' => 'nullable|string',
        ]);

        $loan = Loan::where('loan_number', $loanNumber)->first();

        if (!$loan) {
            $this->error("Loan {$loanNumber} not found.");
            return redirect()->route('admin.loans.index');
        }

        $paymentAmount = (float) $validated['amount'];
        
        // Update loan balance and amount paid
        $loan->update([
            'amount_paid' => $loan->amount_paid + $paymentAmount,
            'balance' => max(0, $loan->balance - $paymentAmount),
        ]);

        // Update repayment schedule status
        $this->updateRepaymentScheduleStatus($loan, $paymentAmount, $validated['payment_date']);

        // Create loan payment record
        \App\Models\LoanPayment::create([
            'loan_id' => $loan->id,
            'payment_number' => 'PAY-' . date('Ymd') . '-' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'payment_date' => $validated['payment_date'],
            'amount' => $paymentAmount,
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'completed',
        ]);

        // Check if loan is fully paid
        if ($loan->balance <= 0) {
            $loan->update(['status' => 'paid']);
        }

        $this->success('Payment recorded successfully.');

        return redirect()->back();
    }

    private function updateRepaymentScheduleStatus(Loan $loan, float $paymentAmount, string $paymentDate)
    {
        $schedules = $loan->repaymentSchedules()->where('status', 'pending')->orderBy('installment_number')->get();
        
        $remainingAmount = $paymentAmount;
        
        foreach ($schedules as $schedule) {
            if ($remainingAmount <= 0) break;
            
            $scheduleAmount = (float) $schedule->total_amount;
            
            if ($remainingAmount >= $scheduleAmount) {
                // Full payment for this installment
                $schedule->update([
                    'status' => 'paid',
                    'amount_paid' => $scheduleAmount,
                    'paid_date' => $paymentDate,
                ]);
                $remainingAmount -= $scheduleAmount;
            } else {
                // Partial payment
                $schedule->update([
                    'status' => 'partial',
                    'amount_paid' => $remainingAmount,
                    'paid_date' => $paymentDate,
                ]);
                $remainingAmount = 0;
            }
        }
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
