<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService,
    ) {
    }

    public function index(Request $request)
    {
        Gate::authorize('admin-only');

        return view('admin.products.index', [
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function create(Request $request)
    {
        Gate::authorize('admin-only');

        return view('admin.products.create', [
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        // TODO: Implement store logic

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Request $request, $id)
    {
        Gate::authorize('admin-only');

        return view('admin.products.edit', [
            'dashboardService' => $this->dashboardService,
            'id' => $id,
        ]);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin-only');

        // TODO: Implement update logic

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('admin-only');

        // TODO: Implement destroy logic

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
