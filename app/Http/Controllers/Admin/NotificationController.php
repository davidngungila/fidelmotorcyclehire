<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NotificationController extends Controller
{
    use FlashMessages;

    public function index(Request $request): View
    {
        Gate::authorize('admin-only');

        $user = Auth::user();

        $notifications = $this->buildNotifications();

        $unreadCount = count(array_filter($notifications, fn($n) => !($n['is_read'] ?? false)));
        $announcementCount = count(array_filter($notifications, fn($n) => ($n['category'] ?? '') === 'announcement'));
        $systemCount = count(array_filter($notifications, fn($n) => ($n['category'] ?? '') === 'system'));

        ActivityLog::create([
            'user_id' => $user->id,
            'subject_type' => 'notification',
            'subject_id' => null,
            'description' => 'Admin viewed notifications',
            'properties' => [
                'unread_count' => $unreadCount,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.notifications.index', compact(
            'notifications',
            'unreadCount',
            'announcementCount',
            'systemCount'
        ));
    }

    protected function buildNotifications(): array
    {
        $notifications = [
            [
                'id' => 'ADMIN-ANNOUNCE-AGM-2026',
                'category' => 'announcement',
                'title' => 'Annual General Meeting 2026',
                'message' => 'Join us on July 30, 2026 at 10:00 AM at the Head Office Conference Hall for our AGM. Agenda includes financial reports and elections.',
                'date' => date('Y-m-d H:i:s', strtotime('-1 day 09:00')),
                'priority' => 'high',
                'is_read' => false,
            ],
            [
                'id' => 'ADMIN-SYSTEM-BACKUP',
                'category' => 'system',
                'title' => 'System Backup Completed',
                'message' => 'Daily database backup completed successfully. Backup file: backup_2026_07_30.sql.gz',
                'date' => date('Y-m-d H:i:s', strtotime('-12 hours 02:00')),
                'priority' => 'normal',
                'is_read' => false,
            ],
            [
                'id' => 'ADMIN-ANNOUNCE-NEW-FEATURE',
                'category' => 'announcement',
                'title' => 'New Feature: SMS Notifications',
                'message' => 'SMS notification system has been enabled. Configure SMS settings in the Settings page to start sending SMS alerts to members.',
                'date' => date('Y-m-d H:i:s', strtotime('-2 days 14:30')),
                'priority' => 'high',
                'is_read' => false,
            ],
            [
                'id' => 'ADMIN-SYSTEM-SECURITY',
                'category' => 'system',
                'title' => 'Security Alert: Failed Login Attempts',
                'message' => 'Multiple failed login attempts detected from IP 192.168.1.100. Please review the activity logs.',
                'date' => date('Y-m-d H:i:s', strtotime('-3 days 08:15')),
                'priority' => 'urgent',
                'is_read' => true,
            ],
            [
                'id' => 'ADMIN-ANNOUNCE-MAINTENANCE',
                'category' => 'announcement',
                'title' => 'Scheduled System Maintenance',
                'message' => 'The portal will be unavailable on Saturday, August 3 from 11:00 PM to 2:00 AM EAT for scheduled maintenance.',
                'date' => date('Y-m-d H:i:s', strtotime('-5 days 10:00')),
                'priority' => 'normal',
                'is_read' => true,
            ],
            [
                'id' => 'ADMIN-SYSTEM-UPDATE',
                'category' => 'system',
                'title' => 'System Update Available',
                'message' => 'Laravel framework update v11.0 is available. Review changelog before updating.',
                'date' => date('Y-m-d H:i:s', strtotime('-1 week 11:00')),
                'priority' => 'low',
                'is_read' => true,
            ],
        ];

        usort($notifications, static fn($a, $b): int => strtotime($b['date']) <=> strtotime($a['date']));

        return $notifications;
    }
}
