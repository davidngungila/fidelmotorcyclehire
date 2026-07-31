<?php

declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Traits\FlashMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class NotificationController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $repository,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('member-only');

        $user = Auth::user();
        $memberNumber = $user->member_number;

        $readNotifications = Session::get('read_notifications', []);
        if (! is_array($readNotifications)) {
            $readNotifications = [];
        }

        $filter = strtolower($request->input('filter', 'all'));

        $notifications = $this->buildNotifications($memberNumber);

        $enriched = array_map(static function (array $n) use ($readNotifications): array {
            $id = (string) ($n['id'] ?? '');
            $isRead = in_array($id, $readNotifications, true);

            return array_merge($n, [
                'is_read' => $isRead,
                'is_unread' => ! $isRead,
            ]);
        }, $notifications);

        $allUnread = array_values(array_filter($enriched, static fn(array $n): bool => ! $n['is_read']));

        if ($filter === 'unread') {
            $displayNotifications = $allUnread;
        } else {
            $displayNotifications = $enriched;
        }

        $unreadCount = count($allUnread);
        $announcementCount = count(array_filter($enriched, static fn(array $n): bool => ($n['category'] ?? '') === 'announcement'));
        $loanReminderCount = count(array_filter($enriched, static fn(array $n): bool => ($n['category'] ?? '') === 'loan'));
        $generalCount = count(array_filter($enriched, static fn(array $n): bool => ($n['category'] ?? '') === 'general'));

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'notifications' => $enriched,
                'unread_count' => $unreadCount,
                'total_count' => count($enriched),
            ]);
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'notification',
            'subject_id' => null,
            'description' => 'Member viewed notifications',
            'properties' => [
                'member_number' => $memberNumber,
                'unread_count' => $unreadCount,
                'filter' => $filter,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('member.notifications.index', compact(
            'displayNotifications',
            'notifications',
            'enriched',
            'unreadCount',
            'announcementCount',
            'loanReminderCount',
            'generalCount',
            'filter',
            'readNotifications'
        ));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        Gate::authorize('member-only');

        $user = Auth::user();
        $memberNumber = $user->member_number;

        $allIds = array_column($this->buildNotifications($memberNumber), 'id');
        $readNotifications = array_unique($allIds);
        
        Session::put('read_notifications', $readNotifications);
        
        $this->success('All notifications marked as read.');

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'notification',
            'subject_id' => null,
            'description' => 'Member marked all notifications as read',
            'properties' => ['member_number' => $memberNumber, 'count' => count($allIds)],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back();
    }

    public function markRead(Request $request, string $id): RedirectResponse
    {
        Gate::authorize('member-only');

        $user = Auth::user();
        $memberNumber = $user->member_number;

        $readNotifications = Session::get('read_notifications', []);
        if (! is_array($readNotifications)) {
            $readNotifications = [];
        }

        if ($id === 'all') {
            $allIds = array_column($this->buildNotifications($memberNumber), 'id');
            $readNotifications = array_unique(array_merge($readNotifications, $allIds));
            $this->success('All notifications marked as read.');

            ActivityLog::create([
                'user_id' => $user->id,
                'subject_type' => 'notification',
                'subject_id' => null,
                'description' => 'Member marked all notifications as read',
                'properties' => ['member_number' => $memberNumber, 'count' => count($allIds)],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } else {
            if (! in_array($id, $readNotifications, true)) {
                $readNotifications[] = $id;
            }
            $this->success('Notification marked as read.');

            ActivityLog::create([
                'user_id' => $user->id,
                'subject_type' => 'notification',
                'subject_id' => null,
                'description' => "Member marked notification as read: {$id}",
                'properties' => ['member_number' => $memberNumber],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Session::put('read_notifications', $readNotifications);

        return redirect()->back();
    }

    protected function buildNotifications(string $memberNumber): array
    {
        $loans = $this->repository->getMemberLoans($memberNumber);
        $now = time();

        $notifications = [];
        $idCounter = 0;

        $announcements = [
            [
                'id' => 'ANNOUNCE-AGM-2026',
                'category' => 'announcement',
                'title' => 'Annual General Meeting 2026',
                'message' => 'Join us on July 30, 2026 at 10:00 AM at the Head Office Conference Hall for our AGM. Agenda includes financial reports and elections.',
                'date' => date('Y-m-d H:i:s', strtotime('-1 day 09:00')),
                'priority' => 'high',
            ],
            [
                'id' => 'ANNOUNCE-RATE-ADJUST',
                'category' => 'announcement',
                'title' => 'Loan Interest Rate Adjustment',
                'message' => 'Effective August 1, 2026, the Emergency Loan interest rate will be revised to 13% p.a. Other loan products remain unchanged.',
                'date' => date('Y-m-d H:i:s', strtotime('-3 days 14:30')),
                'priority' => 'normal',
            ],
            [
                'id' => 'ANNOUNCE-SYSTEM-MAINTENANCE',
                'category' => 'announcement',
                'title' => 'Scheduled System Maintenance',
                'message' => 'The portal will be unavailable on Saturday, July 27 from 11:00 PM to 2:00 AM EAT for scheduled maintenance. We apologize for the inconvenience.',
                'date' => date('Y-m-d H:i:s', strtotime('-5 days 08:00')),
                'priority' => 'normal',
            ],
            [
                'id' => 'ANNOUNCE-SAVINGS-PROMO',
                'category' => 'announcement',
                'title' => 'Summer Savings Boost Campaign',
                'message' => 'Earn a bonus 1.5% interest on all savings deposits made during the month of August! Maximum bonus of TSh 25,000 per member.',
                'date' => date('Y-m-d H:i:s', strtotime('-1 week 11:00')),
                'priority' => 'normal',
            ],
        ];

        foreach ($announcements as $a) {
            $notifications[] = array_merge($a, ['category' => 'announcement']);
        }

        foreach ($loans as $loan) {
            $status = strtolower($loan['status'] ?? '');
            $outstanding = (float) ($loan['outstanding_balance'] ?? 0);
            $installment = (float) ($loan['installment'] ?? 0);
            $maturityDate = $loan['maturity_date'] ?? null;

            if ($status === 'active' && $outstanding > 0) {
                $notifications[] = [
                    'id' => 'LOAN-REM-' . ($loan['loan_number'] ?? 'LN' . $idCounter) . '-INST',
                    'category' => 'loan',
                    'title' => "Upcoming Installment - {$loan['loan_number']}",
                    'message' => "Your next {$loan['loan_product']} installment of TSh " . number_format($installment, 2) . " is due soon. Please ensure sufficient funds are available.",
                    'date' => date('Y-m-d H:i:s', strtotime('+2 days 07:00')),
                    'priority' => 'high',
                ];
                $idCounter++;
            }

            if ($status === 'defaulted') {
                $notifications[] = [
                    'id' => 'LOAN-DEF-' . ($loan['loan_number'] ?? 'LN' . $idCounter),
                    'category' => 'loan',
                    'title' => "Loan Default Alert - {$loan['loan_number']}",
                    'message' => "Your {$loan['loan_product']} is currently in DEFAULT. Outstanding balance: TSh " . number_format($outstanding, 2) . ". Please contact us immediately to arrange repayment.",
                    'date' => date('Y-m-d H:i:s', strtotime('-2 days 08:30')),
                    'priority' => 'urgent',
                ];
                $idCounter++;
            }

            if ($status === 'active' && $maturityDate) {
                $maturityTs = strtotime($maturityDate);
                $daysToMaturity = (int) ceil(($maturityTs - $now) / 86400);
                if ($daysToMaturity > 0 && $daysToMaturity <= 30) {
                    $notifications[] = [
                        'id' => 'LOAN-MAT-' . ($loan['loan_number'] ?? 'LN' . $idCounter),
                        'category' => 'loan',
                        'title' => "Loan Maturing Soon - {$loan['loan_number']}",
                        'message' => "Your {$loan['loan_product']} will mature in {$daysToMaturity} days on {$maturityDate}. Outstanding amount: TSh " . number_format($outstanding, 2) . ".",
                        'date' => date('Y-m-d H:i:s', strtotime('today 10:00')),
                        'priority' => 'normal',
                    ];
                    $idCounter++;
                }
            }

            if ($status === 'settled') {
                $notifications[] = [
                    'id' => 'LOAN-SETTLED-' . ($loan['loan_number'] ?? 'LN' . $idCounter),
                    'category' => 'loan',
                    'title' => "Loan Settled - {$loan['loan_number']}",
                    'message' => "Congratulations! Your {$loan['loan_product']} has been fully settled. Thank you for your timely payments.",
                    'date' => $maturityDate ? date('Y-m-d H:i:s', strtotime($maturityDate . ' 16:00')) : date('Y-m-d H:i:s', strtotime('-2 weeks 16:00')),
                    'priority' => 'normal',
                ];
                $idCounter++;
            }
        }

        $notifications[] = [
            'id' => 'GENERAL-SAVINGS-INT-' . date('Ym'),
            'category' => 'general',
            'title' => 'Monthly Savings Interest Credited',
            'message' => 'Your savings account has been credited with monthly interest. Check your savings ledger for details.',
            'date' => date('Y-m-d H:i:s', strtotime('first day of this month 09:00')),
            'priority' => 'normal',
        ];

        $savings = $this->repository->getMemberSavings($memberNumber);
        if (empty($savings['transactions']) || count($savings['transactions']) === 0) {
            $notifications[] = [
                'id' => 'GENERAL-NO-ACTIVITY',
                'category' => 'general',
                'title' => 'Keep Your Savings Active',
                'message' => 'We noticed your savings account has been quiet recently. Regular small deposits can grow into substantial savings over time!',
                'date' => date('Y-m-d H:i:s', strtotime('-4 days 09:00')),
                'priority' => 'normal',
            ];
        }

        $swf = $this->repository->getMemberSwf($memberNumber);
        $swfBalance = (float) ($swf['current_balance'] ?? 0);
        if ($swfBalance >= 50000) {
            $notifications[] = [
                'id' => 'GENERAL-SWF-MILESTONE',
                'category' => 'general',
                'title' => 'SWF Milestone Achieved!',
                'message' => "Your Social Welfare Fund balance has reached TSh " . number_format($swfBalance, 2) . "! Great job securing your social safety net.",
                'date' => date('Y-m-d H:i:s', strtotime('-1 week 10:00')),
                'priority' => 'normal',
            ];
        }

        $deposits = $this->repository->getMemberDeposits($memberNumber);
        foreach ($deposits as $dep) {
            $maturityDate = $dep['maturity_date'] ?? null;
            if ($maturityDate) {
                $maturityTs = strtotime($maturityDate);
                $daysLeft = (int) ceil(($maturityTs - $now) / 86400);
                if ($daysLeft > 0 && $daysLeft <= 14) {
                    $notifications[] = [
                        'id' => 'GENERAL-FD-MAT-' . ($dep['certificate_number'] ?? 'FD'),
                        'category' => 'general',
                        'title' => "Deposit Maturing: {$dep['certificate_number']}",
                        'message' => "Your {$dep['product']} matures in {$daysLeft} days. Current value: TSh " . number_format((float) ($dep['current_value'] ?? 0), 2) . ". Visit the branch to renew or withdraw.",
                        'date' => date('Y-m-d H:i:s', strtotime('today 08:15')),
                        'priority' => 'normal',
                    ];
                }
            }
        }

        $notifications[] = [
            'id' => 'GENERAL-PROFILE-REMIND',
            'category' => 'general',
            'title' => 'Verify Your Profile Details',
            'message' => 'Please ensure your contact information and next of kin details are up to date. Visit the Profile section to review.',
            'date' => date('Y-m-d H:i:s', strtotime('-2 weeks 11:00')),
            'priority' => 'low',
        ];

        usort($notifications, static fn($a, $b): int => strtotime($b['date']) <=> strtotime($a['date']));

        return $notifications;
    }
}
