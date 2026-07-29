<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentProduct;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class InvestmentProductController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService,
    ) {
    }

    public function index(Request $request)
    {
        Gate::authorize('admin-only');

        $query = InvestmentProduct::query();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.investment-products.index', [
            'dashboardService' => $this->dashboardService,
            'products' => $products,
            'searchQuery' => $request->search,
            'statusFilter' => $request->status,
        ]);
    }

    public function create(Request $request)
    {
        Gate::authorize('admin-only');

        return view('admin.investment-products.create', [
            'dashboardService' => $this->dashboardService,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:investment_products,code',
            'type' => 'required|in:fixed,flexible,mutual_fund,bonds,stocks',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'auto_renew' => 'boolean',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        InvestmentProduct::create($validated);

        return redirect()->route('admin.investment-products.index')
            ->with('success', 'Investment product created successfully.');
    }

    public function edit(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $product = InvestmentProduct::findOrFail($id);

        return view('admin.investment-products.edit', [
            'dashboardService' => $this->dashboardService,
            'product' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $product = InvestmentProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:investment_products,code,' . $id,
            'type' => 'required|in:fixed,flexible,mutual_fund,bonds,stocks',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'auto_renew' => 'boolean',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($validated);

        return redirect()->route('admin.investment-products.index')
            ->with('success', 'Investment product updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('admin-only');

        $product = InvestmentProduct::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.investment-products.index')
            ->with('success', 'Investment product deleted successfully.');
    }
}
