<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StatementController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService,
    ) {
    }

    public function index(Request $request)
    {
        Gate::authorize('admin-only');

        return view('admin.statements.index', [
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function show(Request $request, $id)
    {
        Gate::authorize('admin-only');

        return view('admin.statements.show', [
            'dashboardService' => $this->dashboardService,
            'id' => $id,
        ]);
    }

    public function download(Request $request, $id)
    {
        Gate::authorize('admin-only');

        // TODO: Implement download logic

        return back()->with('success', 'Statement downloaded successfully.');
    }
}
