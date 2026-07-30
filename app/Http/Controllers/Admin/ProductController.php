<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsProduct;
use App\Services\EncryptedIdService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('admin-only');

        $products = SavingsProduct::all();
        $activeCount = SavingsProduct::where('status', 'active')->count();
        $inactiveCount = SavingsProduct::where('status', 'inactive')->count();

        return view('admin.products.index', compact(
            'products',
            'activeCount',
            'inactiveCount'
        ));
    }

    public function create(Request $request): View
    {
        Gate::authorize('admin-only');

        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:savings_products,code',
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_balance' => 'required|numeric|min:0',
            'min_deposit' => 'required|numeric|min:0',
            'max_deposit' => 'nullable|numeric|min:0',
            'min_withdrawal_period_days' => 'required|integer|min:0',
            'premature_withdrawal_fee' => 'required|numeric|min:0|max:100',
            'auto_interest_credit' => 'required|boolean',
            'interest_frequency' => 'required|in:monthly,quarterly,annually',
            'requires_notice' => 'required|boolean',
            'notice_period_days' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        SavingsProduct::create($validated);

        $this->success('Savings product created successfully.');

        return redirect()->route('admin.products.index');
    }

    public function edit(Request $request, string $encryptedId): View
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Invalid product ID.');
        }

        $product = SavingsProduct::findOrFail($id);

        return view('admin.products.edit', compact('product', 'encryptedId'));
    }

    public function update(Request $request, string $encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Invalid product ID.');
        }

        $product = SavingsProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:savings_products,code,' . $id,
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_balance' => 'required|numeric|min:0',
            'min_deposit' => 'required|numeric|min:0',
            'max_deposit' => 'nullable|numeric|min:0',
            'min_withdrawal_period_days' => 'required|integer|min:0',
            'premature_withdrawal_fee' => 'required|numeric|min:0|max:100',
            'auto_interest_credit' => 'required|boolean',
            'interest_frequency' => 'required|in:monthly,quarterly,annually',
            'requires_notice' => 'required|boolean',
            'notice_period_days' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($validated);

        $this->success('Savings product updated successfully.');

        return redirect()->route('admin.products.index');
    }

    public function destroy(Request $request, string $encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Invalid product ID.');
        }

        $product = SavingsProduct::findOrFail($id);
        $product->delete();

        $this->success('Savings product deleted successfully.');

        return redirect()->route('admin.products.index');
    }
}
