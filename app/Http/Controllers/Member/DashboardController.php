<?php

declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Transaction;
use App\Traits\FlashMessages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $repository,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $memberNumber = $user->member_number;

        if (empty($memberNumber)) {
            $this->error('Your account is missing a Member Number. Please contact the administrator to update your profile.');
            return redirect()->route('member.profile.index')->with('hint', 'missing_member_number');
        }

        $member = $this->repository->getMemberByNumber($memberNumber);
        $loans = $this->repository->getMemberLoans($memberNumber);
        $savings = $this->repository->getMemberSavings($memberNumber);
        $deposits = $this->repository->getMemberDeposits($memberNumber);
        $swf = $this->repository->getMemberSwf($memberNumber);
        $investments = $this->repository->getMemberInvestments($memberNumber);

        // Get database transactions
        $dbTransactions = Transaction::byMemberCode($memberNumber)
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'date' => $transaction->date->format('Y-m-d'),
                    'type' => $transaction->transaction_type,
                    'amount' => (float) $transaction->amount,
                    'reference' => $transaction->reference_no ?? '',
                    'balance_after' => null, // Will be calculated
                    'source' => 'database'
                ];
            })
            ->toArray();

        // Merge Google Sheets transactions with database transactions
        $googleTransactions = $savings['transactions'] ?? [];
        $allTransactions = array_merge($googleTransactions, $dbTransactions);

        // Sort by date ascending for balance calculation
        usort($allTransactions, static fn($a, $b): int => strtotime($a['date'] ?? '') <=> strtotime($b['date'] ?? ''));

        // Calculate running balance from all transactions
        $currentBalance = 0;
        foreach ($allTransactions as &$transaction) {
            $type = strtolower($transaction['type'] ?? '');
            $isCredit = $type === 'deposit' || $type === 'flexi-deposit' || $type === 'rda-deposit' || $type === 'opening balance' || $type === 'interest';
            
            if ($isCredit) {
                $currentBalance += (float) ($transaction['amount'] ?? 0);
            } else {
                $currentBalance -= (float) ($transaction['amount'] ?? 0);
            }
            
            $transaction['balance_after'] = $currentBalance;
        }

        // Update savings with calculated balance
        $savings['transactions'] = $allTransactions;
        $savings['running_balance'] = $currentBalance;
        $savings['balance'] = $currentBalance;

        $loanBalance = collect($loans)->sum('outstanding_balance');
        $savingsBalance = $currentBalance;
        $depositBalance = collect($deposits)->sum('current_value');
        $swfBalance = $swf['current_balance'] ?? 0;
        $investmentBalance = collect($investments)->sum('current_value');

        // Filter active loans for dashboard display
        $activeLoans = array_filter($loans, function(array $loan): bool {
            return strtolower($loan['status'] ?? '') === 'active';
        });

        $recentTransactions = $this->consolidateRecentTransactions($loans, $savings, $deposits, $swf, $investments);

        $savingsGrowth = $this->buildSavingsGrowthData($savings);

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'dashboard',
            'subject_id' => null,
            'description' => 'Member viewed dashboard',
            'properties' => ['member_number' => $memberNumber],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.dashboard.index', compact(
            'user',
            'member',
            'loans',
            'activeLoans',
            'savings',
            'deposits',
            'swf',
            'investments',
            'loanBalance',
            'savingsBalance',
            'depositBalance',
            'swfBalance',
            'investmentBalance',
            'recentTransactions',
            'savingsGrowth'
        ));
    }

    protected function consolidateRecentTransactions(array $loans, array $savings, array $deposits, array $swf, array $investments): array
    {
        $transactions = [];

        if (!empty($loans)) {
            foreach ($loans as $loan) {
                if (!empty($loan['paid_amount']) && $loan['paid_amount'] > 0) {
                    $transactions[] = [
                        'date' => $loan['maturity_date'] ?? date('Y-m-d'),
                        'type' => 'Loan Repayment',
                        'description' => "Payment towards {$loan['loan_product']} ({$loan['loan_number']})",
                        'amount' => (float) $loan['paid_amount'],
                        'balance_after' => (float) ($loan['outstanding_balance'] ?? 0),
                        'sort_date' => strtotime($loan['maturity_date'] ?? date('Y-m-d')),
                    ];
                }
                if (!empty($loan['disbursement_date'])) {
                    $transactions[] = [
                        'date' => $loan['disbursement_date'],
                        'type' => 'Loan Disbursement',
                        'description' => "{$loan['loan_product']} disbursed ({$loan['loan_number']})",
                        'amount' => (float) $loan['loan_amount'],
                        'balance_after' => (float) ($loan['outstanding_balance'] ?? $loan['loan_amount']),
                        'sort_date' => strtotime($loan['disbursement_date']),
                    ];
                }
            }
        }

        // Include database transactions in recent transactions
        if (!empty($savings['transactions']) && is_array($savings['transactions'])) {
            foreach ($savings['transactions'] as $txn) {
                $typeLabel = match (strtolower($txn['type'] ?? '')) {
                    'deposit' => 'Saving Deposit',
                    'withdrawal' => 'Saving Withdrawal',
                    'interest' => 'Savings Interest',
                    'flexi-deposit' => 'Flexi Deposit',
                    'rda-deposit' => 'RDA Deposit',
                    'opening balance' => 'Opening Balance',
                    default => 'Saving ' . ($txn['type'] ?? 'Transaction'),
                };
                $transactions[] = [
                    'date' => $txn['date'],
                    'type' => $typeLabel,
                    'description' => $txn['reference'] ?? ($txn['description'] ?? 'Savings transaction'),
                    'amount' => (float) $txn['amount'],
                    'balance_after' => (float) ($txn['balance_after'] ?? 0),
                    'sort_date' => strtotime($txn['date']),
                ];
            }
        }

        if (!empty($deposits)) {
            foreach ($deposits as $dep) {
                if (!empty($dep['maturity_date'])) {
                    $transactions[] = [
                        'date' => $dep['maturity_date'],
                        'type' => 'Deposit Maturity',
                        'description' => "{$dep['product']} matures ({$dep['certificate_number']})",
                        'amount' => (float) ($dep['current_value'] ?? $dep['amount']),
                        'balance_after' => (float) ($dep['current_value'] ?? 0),
                        'sort_date' => strtotime($dep['maturity_date']),
                    ];
                }
                if (!empty($dep['start_date'])) {
                    $transactions[] = [
                        'date' => $dep['start_date'],
                        'type' => 'Deposit Placement',
                        'description' => "Opened {$dep['product']} ({$dep['certificate_number']})",
                        'amount' => (float) $dep['amount'],
                        'balance_after' => (float) ($dep['current_value'] ?? $dep['amount']),
                        'sort_date' => strtotime($dep['start_date']),
                    ];
                }
            }
        }

        if (!empty($swf['contribution_history']) && is_array($swf['contribution_history'])) {
            foreach ($swf['contribution_history'] as $c) {
                $transactions[] = [
                    'date' => $c['date'],
                    'type' => 'SWF Contribution',
                    'description' => $c['description'] ?? 'Social Welfare Fund',
                    'amount' => (float) $c['amount'],
                    'balance_after' => (float) ($swf['current_balance'] ?? 0),
                    'sort_date' => strtotime($c['date']),
                ];
            }
        }

        usort($transactions, static fn($a, $b): int => ($b['sort_date'] ?? 0) <=> ($a['sort_date'] ?? 0));

        return array_slice($transactions, 0, 5);
    }

    protected function buildSavingsGrowthData(array $savings): array
    {
        $labels = [];
        $values = [];
        $today = new \DateTime();

        for ($i = 5; $i >= 0; $i--) {
            $d = (clone $today)->modify("-{$i} month");
            $labels[] = $d->format('M Y');
        }

        $currentBalance = (float) ($savings['running_balance'] ?? $savings['balance'] ?? 0);
        
        // Calculate actual growth from transaction history
        if (!empty($savings['transactions']) && is_array($savings['transactions'])) {
            $sorted = $savings['transactions'];
            usort($sorted, static fn($a, $b): int => strtotime($a['date'] ?? '') <=> strtotime($b['date'] ?? ''));
            
            // Get balance at 6 months ago
            $sixMonthsAgo = (clone $today)->modify('-6 months')->format('Y-m-d');
            $startBalance = 0;
            
            foreach ($sorted as $txn) {
                if (strtotime($txn['date'] ?? '') >= strtotime($sixMonthsAgo)) {
                    $startBalance = (float) ($txn['balance_after'] ?? 0);
                    break;
                }
            }
            
            // If no transaction found in 6 months, use first transaction balance
            if ($startBalance === 0 && !empty($sorted)) {
                $startBalance = (float) ($sorted[0]['balance_after'] ?? 0);
            }
        } else {
            $startBalance = max(0, $currentBalance - 50000);
        }

        if ($currentBalance < $startBalance) {
            $startBalance = $currentBalance;
        }

        $step = $currentBalance > $startBalance ? ($currentBalance - $startBalance) / 5 : 0;
        for ($i = 0; $i < 6; $i++) {
            $values[] = round($startBalance + ($step * $i), 2);
        }
        if (!empty($values)) {
            $values[count($values) - 1] = $currentBalance;
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
