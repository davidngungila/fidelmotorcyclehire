<?php

declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\EncryptedIdService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LoanController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $repository,
        protected EncryptedIdService $encryptedIdService,
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('member-only');

        $user = Auth::user();
        $memberNumber = $user->member_number;

        $loans = $this->repository->getMemberLoans($memberNumber);

        $processedLoans = array_map(function (array $loan): array {
            $totalAmount = (float) ($loan['loan_amount'] ?? 0);
            $paid = (float) ($loan['paid_amount'] ?? 0);
            $outstanding = (float) ($loan['outstanding_balance'] ?? 0);
            $progress = $totalAmount > 0 ? min(100, round(($paid / $totalAmount) * 100, 1)) : 0;

            return array_merge($loan, [
                'progress_percent' => $progress,
                'total_amount' => $totalAmount,
                'paid_amount_float' => $paid,
                'outstanding_float' => $outstanding,
            ]);
        }, $loans);

        $totalOutstanding = array_sum(array_column($processedLoans, 'outstanding_float'));
        $totalBorrowed = array_sum(array_column($processedLoans, 'total_amount'));
        $activeCount = count(array_filter($processedLoans, static fn(array $l): bool => strtolower($l['status'] ?? '') === 'active'));

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'loan',
            'subject_id' => null,
            'description' => 'Member viewed loans',
            'properties' => ['member_number' => $memberNumber, 'loan_count' => count($processedLoans)],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.loans.index', compact(
            'processedLoans',
            'loans',
            'totalOutstanding',
            'totalBorrowed',
            'activeCount'
        ));
    }

    public function show(Request $request, string $encryptedLoanNumber): View
    {
        $loanNumber = $this->encryptedIdService->decrypt($encryptedLoanNumber);
        
        Gate::authorize('member-only');

        $user = Auth::user();
        $memberNumber = $user->member_number;

        $loans = $this->repository->getMemberLoans($memberNumber);
        $loan = collect($loans)->firstWhere('loan_number', $loanNumber);

        if (! $loan) {
            $this->error("Loan {$loanNumber} not found or access denied.");
            abort(404, 'Loan not found');
        }

        $totalAmount = (float) ($loan['loan_amount'] ?? 0);
        $paid = (float) ($loan['paid_amount'] ?? 0);
        $outstanding = (float) ($loan['outstanding_balance'] ?? 0);
        $progress = $totalAmount > 0 ? min(100, round(($paid / $totalAmount) * 100, 1)) : 0;

        $repaymentSchedule = $this->buildRepaymentSchedule($loan);
        $repaymentHistory = $this->buildRepaymentHistory($loan);

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'loan',
            'subject_id' => null,
            'description' => "Member viewed loan: {$loanNumber}",
            'properties' => ['member_number' => $memberNumber, 'loan_product' => $loan['loan_product'] ?? null],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.loans.show', compact(
            'loan',
            'loanNumber',
            'totalAmount',
            'paid',
            'outstanding',
            'progress',
            'repaymentSchedule',
            'repaymentHistory'
        ));
    }

    protected function buildRepaymentSchedule(array $loan): array
    {
        $installment = (float) ($loan['installment'] ?? 0);
        $totalAmount = (float) ($loan['loan_amount'] ?? 0);
        $interestRate = (float) ($loan['interest_rate'] ?? 0) / 100;
        $disbursementDate = $loan['disbursement_date'] ?? date('Y-m-d');

        if ($installment <= 0 || $totalAmount <= 0) {
            return [];
        }

        $months = (int) ceil($totalAmount / $installment);
        if ($months > 60) {
            $months = (int) round($totalAmount / $installment);
        }
        $months = max(1, min($months, 60));

        $schedule = [];
        $remaining = $totalAmount;
        $currentDate = new \DateTime($disbursementDate);

        for ($i = 1; $i <= $months; $i++) {
            $currentDate->modify('+1 month');
            $interestPortion = round($remaining * ($interestRate / 12), 2);
            $principalPortion = round($installment - $interestPortion, 2);
            $remaining = round(max(0, $remaining - $principalPortion), 2);

            if ($i === $months && $remaining > 0) {
                $principalPortion += $remaining;
                $remaining = 0;
            }

            $schedule[] = [
                'installment_no' => $i,
                'due_date' => $currentDate->format('Y-m-d'),
                'installment' => round($principalPortion + $interestPortion, 2),
                'principal' => $principalPortion,
                'interest' => $interestPortion,
                'remaining' => $remaining,
                'status' => $remaining === 0 ? 'Paid' : (strtotime($currentDate->format('Y-m-d')) < time() ? 'Overdue' : 'Pending'),
            ];
        }

        return $schedule;
    }

    protected function buildRepaymentHistory(array $loan): array
    {
        $paid = (float) ($loan['paid_amount'] ?? 0);
        $installment = (float) ($loan['installment'] ?? 0);
        $disbursementDate = $loan['disbursement_date'] ?? date('Y-m-d');

        if ($paid <= 0 || $installment <= 0) {
            return [];
        }

        $fullPayments = (int) floor($paid / $installment);
        $history = [];
        $currentDate = new \DateTime($disbursementDate);

        for ($i = 1; $i <= $fullPayments; $i++) {
            $currentDate->modify('+1 month');
            $history[] = [
                'payment_no' => $i,
                'date' => $currentDate->format('Y-m-d'),
                'amount' => $installment,
                'method' => 'Payroll Deduction',
                'reference' => 'PY-' . strtoupper(substr(md5($loan['loan_number'] . $i), 0, 8)),
                'status' => 'Completed',
            ];
        }

        $partial = round($paid - ($fullPayments * $installment), 2);
        if ($partial > 0) {
            $currentDate->modify('+1 month');
            $history[] = [
                'payment_no' => $fullPayments + 1,
                'date' => $currentDate->format('Y-m-d'),
                'amount' => $partial,
                'method' => 'Partial Payment',
                'reference' => 'PP-' . strtoupper(substr(md5($loan['loan_number'] . 'partial'), 0, 8)),
                'status' => 'Completed',
            ];
        }

        return array_reverse($history);
    }
}
