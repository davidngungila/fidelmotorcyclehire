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

class SwfController extends Controller
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

        $swf = $this->repository->getMemberSwf($memberNumber);

        $totalContribution = (float) ($swf['total_contribution'] ?? 0);
        $benefits = (float) ($swf['benefits'] ?? 0);
        $currentBalance = (float) ($swf['current_balance'] ?? 0);

        $contributionHistory = $swf['contribution_history'] ?? [];

        $processedHistory = array_map(static function (array $c): array {
            return array_merge($c, [
                'amount_float' => (float) ($c['amount'] ?? 0),
            ]);
        }, $contributionHistory);

        usort($processedHistory, static fn($a, $b): int => strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? ''));

        $monthsContributed = count($processedHistory);
        $avgContribution = $monthsContributed > 0 ? round($totalContribution / $monthsContributed, 2) : 0;

        $benefitsInfo = [
            [
                'title' => 'Funeral Cover',
                'amount' => 300000,
                'description' => 'Beneficiary support in case of member demise.',
                'icon' => 'fa-heart',
                'color' => 'red',
            ],
            [
                'title' => 'Medical Emergency',
                'amount' => 100000,
                'description' => 'Hospital bill support for serious conditions.',
                'icon' => 'fa-kit-medical',
                'color' => 'blue',
            ],
            [
                'title' => 'Education Grant',
                'amount' => 50000,
                'description' => 'Annual grant for dependent school fees.',
                'icon' => 'fa-graduation-cap',
                'color' => 'purple',
            ],
            [
                'title' => 'Welfare Assistance',
                'amount' => 25000,
                'description' => 'General welfare & hardship support.',
                'icon' => 'fa-hand-holding-heart',
                'color' => 'yellow',
            ],
        ];

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'swf',
            'subject_id' => null,
            'description' => 'Member viewed SWF',
            'properties' => [
                'member_number' => $memberNumber,
                'current_balance' => $currentBalance,
                'contribution_count' => count($processedHistory),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.swf.index', compact(
            'swf',
            'totalContribution',
            'benefits',
            'currentBalance',
            'processedHistory',
            'contributionHistory',
            'monthsContributed',
            'avgContribution',
            'benefitsInfo'
        ));
    }
}
