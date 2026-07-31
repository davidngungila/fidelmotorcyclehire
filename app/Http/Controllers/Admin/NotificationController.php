<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
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

        $notifications = Notification::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();
        $announcementCount = $notifications->where('category', 'announcement')->count();
        $systemCount = $notifications->where('category', 'system')->count();

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

    public function create(Request $request): View
    {
        Gate::authorize('admin-only');

        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'category' => ['required', 'in:announcement,system,alert'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'priority' => ['required', 'in:urgent,high,normal,low'],
        ]);

        $notification = Notification::create([
            'category' => $validated['category'],
            'title' => $validated['title'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'is_read' => false,
            'created_by' => Auth::id(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'notification',
            'subject_id' => $notification->id,
            'description' => 'Admin created notification',
            'properties' => [
                'category' => $notification->category,
                'title' => $notification->title,
                'priority' => $notification->priority,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Notification created successfully.');
        return redirect()->route('admin.notifications.index');
    }

    public function markAsRead(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $notification = Notification::findOrFail($id);
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'notification',
            'subject_id' => $notification->id,
            'description' => 'Admin marked notification as read',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $notification = Notification::findOrFail($id);
        $notification->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'notification',
            'subject_id' => $id,
            'description' => 'Admin deleted notification',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Notification deleted successfully.');
        return redirect()->back();
    }
}
