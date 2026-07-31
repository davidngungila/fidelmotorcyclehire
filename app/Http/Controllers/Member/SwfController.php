<?php

declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SwfMember;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SwfController extends Controller
{
    use FlashMessages;

    public function index(Request $request): View
    {
        Gate::authorize('member-only');

        $user = Auth::user();
        $swfMember = $user->swfMember;

        if (!$swfMember) {
            return view('member.swf.index', [
                'swfMember' => null,
                'totalContribution' => 0,
                'benefits' => 0,
                'currentBalance' => 0,
                'processedHistory' => [],
                'contributionHistory' => [],
                'monthsContributed' => 0,
                'avgContribution' => 0,
                'benefitsInfo' => [],
            ]);
        }

        $swfMember->load(['contributions', 'benefits']);

        $totalContribution = $swfMember->total_contributions;
        $benefits = $swfMember->total_benefits_received;
        $currentBalance = $swfMember->total_contributions - $swfMember->total_benefits_received;

        $contributionHistory = $swfMember->contributions->map(function ($contribution) {
            return [
                'date' => $contribution->contribution_date->format('Y-m-d'),
                'amount' => $contribution->amount,
                'payment_method' => $contribution->payment_method,
                'reference_number' => $contribution->reference_number,
            ];
        })->toArray();

        $processedHistory = array_map(static function (array $c): array {
            return array_merge($c, [
                'amount_float' => (float) ($c['amount'] ?? 0),
            ]);
        }, $contributionHistory);

        usort($processedHistory, static fn($a, $b): int => strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? ''));

        $monthsContributed = count($processedHistory);
        $avgContribution = $monthsContributed > 0 ? round($totalContribution / $monthsContributed, 2) : 0;

        $benefitsInfo = $swfMember->benefits->map(function ($benefit) {
            return [
                'title' => $benefit->name,
                'amount' => $benefit->pivot->amount,
                'description' => $benefit->description,
                'icon' => 'fa-gift',
                'color' => 'purple',
            ];
        })->toArray();

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'swf_member',
            'subject_id' => $swfMember->id,
            'description' => 'Member viewed SWF',
            'properties' => [
                'membership_number' => $swfMember->membership_number,
                'current_balance' => $currentBalance,
                'contribution_count' => count($processedHistory),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.swf.index', compact(
            'swfMember',
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
