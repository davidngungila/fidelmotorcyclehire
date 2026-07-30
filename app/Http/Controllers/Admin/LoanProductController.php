<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanProduct;
use App\Services\EncryptedIdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class LoanProductController extends Controller
{
    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request)
    {
        Gate::authorize('admin-only');

        $searchQuery = $request->input('q', '');
        $statusFilter = $request->input('status', '');

        $query = LoanProduct::query();

        if (!empty($searchQuery)) {
            $query->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('code', 'like', '%' . $searchQuery . '%');
        }

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $loanProducts = $query->orderBy('name')->paginate(15);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed loan products list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.loan-products.index', [
            'loanProducts' => $loanProducts,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        Gate::authorize('admin-only');

        return view('admin.loan-products.create');
    }

    public function show(Request $request, string $encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.loan-products.index')
                ->with('error', 'Invalid loan product ID.');
        }

        $loanProduct = LoanProduct::findOrFail($id);
        $loans = $loanProduct->loans()->orderBy('created_at', 'desc')->paginate(10);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed loan product details: ' . $loanProduct->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.loan-products.show', compact('loanProduct', 'encryptedId', 'loans'));
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:loan_products,code',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0|gte:min_amount',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_term_months' => 'required|integer|min:1',
            'max_term_months' => 'required|integer|min:1|gte:min_term_months',
            'processing_fee' => 'required|numeric|min:0',
            'late_fee' => 'required|numeric|min:0',
            'interest_type' => 'required|in:flat,reducing,compound',
            'repayment_frequency' => 'required|in:monthly,weekly,bi_weekly',
            'requires_collateral' => 'boolean',
            'requires_guarantor' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        LoanProduct::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin created loan product: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.loan-products.index')
            ->with('success', 'Loan product created successfully.');
    }

    public function edit($encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.loan-products.index')
                ->with('error', 'Invalid loan product ID.');
        }

        $loanProduct = LoanProduct::findOrFail($id);

        return view('admin.loan-products.edit', [
            'loanProduct' => $loanProduct,
        ]);
    }

    public function update(Request $request, $encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.loan-products.index')
                ->with('error', 'Invalid loan product ID.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:loan_products,code,' . $id,
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0|gte:min_amount',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_term_months' => 'required|integer|min:1',
            'max_term_months' => 'required|integer|min:1|gte:min_term_months',
            'processing_fee' => 'required|numeric|min:0',
            'late_fee' => 'required|numeric|min:0',
            'interest_type' => 'required|in:flat,reducing,compound',
            'repayment_frequency' => 'required|in:monthly,weekly,bi_weekly',
            'requires_collateral' => 'boolean',
            'requires_guarantor' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $loanProduct = LoanProduct::findOrFail($id);
        $loanProduct->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin updated loan product: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.loan-products.index')
            ->with('success', 'Loan product updated successfully.');
    }

    public function destroy($encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.loan-products.index')
                ->with('error', 'Invalid loan product ID.');
        }

        $loanProduct = LoanProduct::findOrFail($id);
        $loanProductName = $loanProduct->name;

        // Check if there are any loans using this product
        if ($loanProduct->loans()->count() > 0) {
            return redirect()->route('admin.loan-products.index')
                ->with('error', 'Cannot delete loan product. There are loans associated with this product.');
        }

        $loanProduct->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin deleted loan product: ' . $loanProductName,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.loan-products.index')
            ->with('success', 'Loan product deleted successfully.');
    }
}
