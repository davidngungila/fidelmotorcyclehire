<?php

declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InvestmentController extends Controller
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

        $investments = $this->repository->getMemberInvestments($memberNumber);

        $processedInvestments = array_map(function (array $inv): array {
            $amountInvested = (float) ($inv['amount_invested'] ?? 0);
            $currentValue = (float) ($inv['current_value'] ?? 0);
            $profitEarned = (float) ($inv['profit_earned'] ?? 0);
            $returnRate = (float) ($inv['return_rate'] ?? 0);

            $isProfit = $profitEarned >= 0;
            $sparkline = $this->generateSparkline($inv['history'] ?? [], $currentValue, $amountInvested);

            return array_merge($inv, [
                'amount_invested_float' => $amountInvested,
                'current_value_float' => $currentValue,
                'profit_earned_float' => $profitEarned,
                'return_rate_float' => $returnRate,
                'is_profit' => $isProfit,
                'sparkline' => $sparkline,
            ]);
        }, $investments);

        $totalInvested = array_sum(array_column($processedInvestments, 'amount_invested_float'));
        $totalValue = array_sum(array_column($processedInvestments, 'current_value_float'));
        $totalProfit = $totalValue - $totalInvested;
        $overallReturn = $totalInvested > 0 ? round(($totalProfit / $totalInvested) * 100, 2) : 0;
        $productsCount = count($processedInvestments);

        $transactionHistory = [];
        foreach ($processedInvestments as $inv) {
            if (! empty($inv['history']) && is_array($inv['history'])) {
                foreach ($inv['history'] as $event) {
                    $transactionHistory[] = [
                        'date' => $event['date'] ?? '',
                        'product' => $inv['product'] ?? 'Investment',
                        'type' => $event['type'] ?? 'Transaction',
                        'value' => (float) ($event['value'] ?? 0),
                        'units' => $inv['units'] ?? null,
                    ];
                }
            }
        }
        usort($transactionHistory, static fn($a, $b): int => strtotime($b['date']) <=> strtotime($a['date']));

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'investment',
            'subject_id' => null,
            'description' => 'Member viewed investments',
            'properties' => [
                'member_number' => $memberNumber,
                'investment_count' => $productsCount,
                'total_value' => $totalValue,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.investments.index', compact(
            'processedInvestments',
            'investments',
            'totalInvested',
            'totalValue',
            'totalProfit',
            'overallReturn',
            'productsCount',
            'transactionHistory'
        ));
    }

    protected function generateSparkline(?array $history, float $currentValue, float $startValue): array
    {
        $points = 8;
        $sparkline = [];

        if (! empty($history) && is_array($history)) {
            $sorted = $history;
            usort($sorted, static fn($a, $b): int => strtotime($a['date'] ?? '') <=> strtotime($b['date'] ?? ''));
            $values = array_map(static fn($h) => (float) ($h['value'] ?? 0), $sorted);

            if (count($values) >= 2) {
                $step = (count($values) - 1) / ($points - 1);
                for ($i = 0; $i < $points; $i++) {
                    $idx = (int) floor($i * $step);
                    $nextIdx = min(count($values) - 1, $idx + 1);
                    $frac = ($i * $step) - $idx;
                    $val = $values[$idx] + ($values[$nextIdx] - $values[$idx]) * $frac;
                    $sparkline[] = round($val, 2);
                }
            } else {
                $sparkline = array_fill(0, $points, $startValue);
            }
        } else {
            $step = ($currentValue - $startValue) / ($points - 1);
            for ($i = 0; $i < $points; $i++) {
                $sparkline[] = round($startValue + ($step * $i), 2);
            }
        }

        if (! empty($sparkline)) {
            $sparkline[count($sparkline) - 1] = $currentValue;
        }

        return $sparkline;
    }
}
