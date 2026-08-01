<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareProductController extends Controller
{
    public function index()
    {
        $shareProducts = ShareProduct::latest()->paginate(10);
        return view('admin.share-products.index', compact('shareProducts'));
    }

    public function create()
    {
        return view('admin.share-products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:share_products,code|max:50',
            'description' => 'nullable|string',
            'price_per_share' => 'required|numeric|min:0',
            'minimum_shares' => 'required|integer|min:1',
            'maximum_shares' => 'nullable|integer|min:1',
            'dividend_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,closed',
            'issue_date' => 'nullable|date',
            'maturity_date' => 'nullable|date|after:issue_date',
        ]);

        ShareProduct::create($validated);

        return redirect()->route('admin.share-products.index')
            ->with('success', 'Share product created successfully.');
    }

    public function show(ShareProduct $shareProduct)
    {
        return view('admin.share-products.show', compact('shareProduct'));
    }

    public function edit(ShareProduct $shareProduct)
    {
        return view('admin.share-products.edit', compact('shareProduct'));
    }

    public function update(Request $request, ShareProduct $shareProduct)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:share_products,code,' . $shareProduct->id . '|max:50',
            'description' => 'nullable|string',
            'price_per_share' => 'required|numeric|min:0',
            'minimum_shares' => 'required|integer|min:1',
            'maximum_shares' => 'nullable|integer|min:1',
            'dividend_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,closed',
            'issue_date' => 'nullable|date',
            'maturity_date' => 'nullable|date|after:issue_date',
        ]);

        $shareProduct->update($validated);

        return redirect()->route('admin.share-products.index')
            ->with('success', 'Share product updated successfully.');
    }

    public function destroy(ShareProduct $shareProduct)
    {
        $shareProduct->delete();

        return redirect()->route('admin.share-products.index')
            ->with('success', 'Share product deleted successfully.');
    }
}
