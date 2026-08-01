<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SharePurchase;
use App\Models\ShareProduct;
use App\Models\User;
use Illuminate\Http\Request;

class SharePurchaseController extends Controller
{
    public function index()
    {
        $sharePurchases = SharePurchase::with(['user', 'shareProduct'])->latest()->paginate(10);
        return view('admin.share-purchases.index', compact('sharePurchases'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->get();
        $shareProducts = ShareProduct::where('status', 'active')->get();
        return view('admin.share-purchases.create', compact('users', 'shareProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'share_product_id' => 'required|exists:share_products,id',
            'number_of_shares' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        SharePurchase::create($validated);

        return redirect()->route('admin.share-purchases.index')
            ->with('success', 'Share purchase created successfully.');
    }

    public function show(SharePurchase $sharePurchase)
    {
        $sharePurchase->load(['user', 'shareProduct', 'shareCertificates']);
        return view('admin.share-purchases.show', compact('sharePurchase'));
    }

    public function edit(SharePurchase $sharePurchase)
    {
        $users = User::where('role', 'member')->get();
        $shareProducts = ShareProduct::where('status', 'active')->get();
        return view('admin.share-purchases.edit', compact('sharePurchase', 'users', 'shareProducts'));
    }

    public function update(Request $request, SharePurchase $sharePurchase)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'share_product_id' => 'required|exists:share_products,id',
            'number_of_shares' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        $sharePurchase->update($validated);

        return redirect()->route('admin.share-purchases.index')
            ->with('success', 'Share purchase updated successfully.');
    }

    public function destroy(SharePurchase $sharePurchase)
    {
        $sharePurchase->delete();

        return redirect()->route('admin.share-purchases.index')
            ->with('success', 'Share purchase deleted successfully.');
    }
}
