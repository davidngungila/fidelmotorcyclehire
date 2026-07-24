<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\AdminDashboardService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $googleSheetRepository,
        protected AdminDashboardService $dashboardService,
    ) {
    }

    public function index(Request $request)
    {
        $totals = $this->googleSheetRepository->getDashboardTotals();
        $lastSync = $this->googleSheetRepository->getLastSyncInfo();

        $searchResults = null;
        if ($request->filled('q')) {
            $searchResults = $this->googleSheetRepository->searchMembers($request->input('q'));
        }

        $allMembers = $this->googleSheetRepository->getAllMembers();
        $recentMembers = array_slice($allMembers, 0, 5);

        $formattedTotals = $this->dashboardService->formatTotals(array_merge(
            $totals,
            [
                'last_sync' => $lastSync['last_sync_at'] ?? 'Never',
                'google_sheet_status' => $lastSync['status'] ?? 'unknown',
            ]
        ));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed dashboard',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'has_search' => $request->filled('q'),
                'search_query' => $request->input('q'),
            ],
        ]);

        return view('admin.dashboard.index', [
            'totals' => $formattedTotals,
            'rawTotals' => $totals,
            'recentMembers' => $recentMembers,
            'searchResults' => $searchResults,
            'searchQuery' => $request->input('q'),
            'lastSync' => $lastSync,
            'dashboardService' => $this->dashboardService,
        ]);
    }
}
