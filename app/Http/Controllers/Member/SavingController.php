<?php

declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Transaction;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SavingController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $repository,
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('member-only');

        $user = Auth::user();
        $memberNumber = $user->member_number;

        $savings = $this->repository->getMemberSavings($memberNumber);

        $balance = (float) ($savings['balance'] ?? 0);
        $interestEarned = (float) ($savings['interest_earned'] ?? 0);
        $runningBalance = (float) ($savings['running_balance'] ?? 0);

        // Get transactions from Google Sheets
        $googleTransactions = $savings['transactions'] ?? [];

        // Get transactions from database
        $dbTransactions = Transaction::byMemberCode($memberNumber)
            ->orderBy('date', 'desc')
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

        // Merge transactions from both sources
        $allTransactions = array_merge($googleTransactions, $dbTransactions);

        // Sort by date
        usort($allTransactions, static fn($a, $b): int => strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? ''));

        $transactions = $allTransactions;

        $deposits = array_values(array_filter($transactions, static function (array $t): bool {
            $type = strtolower($t['type'] ?? '');
            return $type === 'deposit' || ($type !== 'withdrawal' && $type !== 'interest' && ($t['amount'] ?? 0) > 0);
        }));

        $withdrawals = array_values(array_filter($transactions, static function (array $t): bool {
            $type = strtolower($t['type'] ?? '');
            return $type === 'withdrawal' || ($t['amount'] ?? 0) < 0;
        }));

        $ledger = array_map(static function (array $t): array {
            $amount = (float) ($t['amount'] ?? 0);
            $type = strtolower($t['type'] ?? '');
            $isCredit = $type === 'deposit' || $type === 'interest' || $amount > 0;

            return array_merge($t, [
                'amount_float' => $amount,
                'is_credit' => $isCredit,
                'credit' => $isCredit ? abs($amount) : 0,
                'debit' => ! $isCredit ? abs($amount) : 0,
                'balance_after' => (float) ($t['balance_after'] ?? 0),
            ]);
        }, $transactions);

        $totalDeposited = array_sum(array_column($deposits, 'amount'));
        $totalWithdrawn = abs(array_sum(array_column($withdrawals, 'amount')));

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'savings',
            'subject_id' => null,
            'description' => 'Member viewed savings',
            'properties' => [
                'member_number' => $memberNumber,
                'balance' => $balance,
                'transaction_count' => count($transactions),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.savings.index', compact(
            'savings',
            'balance',
            'interestEarned',
            'runningBalance',
            'deposits',
            'withdrawals',
            'ledger',
            'totalDeposited',
            'totalWithdrawn'
        ));
    }
}
