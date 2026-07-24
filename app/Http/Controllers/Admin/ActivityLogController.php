<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $filterUser = $request->input('user', '');
        $filterAction = $request->input('action', '');
        $filterDateFrom = $request->input('date_from', '');
        $filterDateTo = $request->input('date_to', '');

        $query = ActivityLog::latest()->with('user');

        if (! empty($filterUser)) {
            if (is_numeric($filterUser)) {
                $query->where('user_id', (int) $filterUser);
            } else {
                $query->whereHas('user', function ($q) use ($filterUser) {
                    $q->where('name', 'like', "%{$filterUser}%")
                        ->orWhere('email', 'like', "%{$filterUser}%");
                });
            }
        }

        if (! empty($filterAction)) {
            $query->where('description', 'like', "%{$filterAction}%");
        }

        if (! empty($filterDateFrom)) {
            $query->whereDate('created_at', '>=', $filterDateFrom);
        }

        if (! empty($filterDateTo)) {
            $query->whereDate('created_at', '<=', $filterDateTo);
        }

        $activityLogs = $query->paginate($perPage);

        $activityLogs->appends([
            'user' => $filterUser,
            'action' => $filterAction,
            'date_from' => $filterDateFrom,
            'date_to' => $filterDateTo,
            'per_page' => $perPage,
        ]);

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed activity logs',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'filter_user' => $filterUser,
                'filter_action' => $filterAction,
                'filter_date_from' => $filterDateFrom,
                'filter_date_to' => $filterDateTo,
                'total_records' => $activityLogs->total(),
            ],
        ]);

        return view('admin.activity-logs.index', [
            'activityLogs' => $activityLogs,
            'users' => $users,
            'filterUser' => $filterUser,
            'filterAction' => $filterAction,
            'filterDateFrom' => $filterDateFrom,
            'filterDateTo' => $filterDateTo,
            'perPage' => $perPage,
        ]);
    }
}
