<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareTransaction;
use App\Models\ShareProduct;
use App\Models\User;
use Illuminate\Http\Request;

class ShareTransactionController extends Controller
{
    public function index()
    {
        $shareTransactions = ShareTransaction::with(['user', 'shareProduct'])->latest()->paginate(10);
        return view('admin.share-transactions.index', compact('shareTransactions'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->get();
        $shareProducts = ShareProduct::where('status', 'active')->get();
        return view('admin.share-transactions.create', compact('users', 'shareProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'share_product_id' => 'required|exists:share_products,id',
            'transaction_type' => 'required|in:purchase,sale,transfer,dividend',
            'number_of_shares' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        ShareTransaction::create($validated);

        return redirect()->route('admin.share-transactions.index')
            ->with('success', 'Share transaction created successfully.');
    }

    public function show(ShareTransaction $shareTransaction)
    {
        $shareTransaction->load(['user', 'shareProduct']);
        return view('admin.share-transactions.show', compact('shareTransaction'));
    }

    public function edit(ShareTransaction $shareTransaction)
    {
        $users = User::where('role', 'member')->get();
        $shareProducts = ShareProduct::where('status', 'active')->get();
        return view('admin.share-transactions.edit', compact('shareTransaction', 'users', 'shareProducts'));
    }

    public function update(Request $request, ShareTransaction $shareTransaction)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'share_product_id' => 'required|exists:share_products,id',
            'transaction_type' => 'required|in:purchase,sale,transfer,dividend',
            'number_of_shares' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $shareTransaction->update($validated);

        return redirect()->route('admin.share-transactions.index')
            ->with('success', 'Share transaction updated successfully.');
    }

    public function destroy(ShareTransaction $shareTransaction)
    {
        $shareTransaction->delete();

        return redirect()->route('admin.share-transactions.index')
            ->with('success', 'Share transaction deleted successfully.');
    }
}
