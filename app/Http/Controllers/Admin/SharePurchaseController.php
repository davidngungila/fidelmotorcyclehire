<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\SharePurchase;
use App\Models\ShareProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $sharePurchase = SharePurchase::create($validated);

        // Create journal entry for share purchase (double-entry)
        if ($validated['payment_status'] === 'paid') {
            $this->createSharePurchaseJournalEntry($sharePurchase);
        }

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

    private function createSharePurchaseJournalEntry(SharePurchase $sharePurchase)
    {
        // Get share capital account (equity) and cash/bank account
        $shareCapitalAccount = Account::where('account_type', 'equity')
            ->where('account_subtype', 'share_capital')
            ->where('is_active', true)
            ->first();

        $cashAccount = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->first();

        if (!$shareCapitalAccount || !$cashAccount) {
            \Log::error('Required accounts not found for share purchase journal entry', [
                'share_purchase_id' => $sharePurchase->id,
            ]);
            return;
        }

        $user = User::find($sharePurchase->user_id);
        $userName = $user ? $user->name : 'Unknown';

        // Create journal entry
        $journalEntry = JournalEntry::create([
            'entry_number' => 'SHARE-' . date('Ymd') . '-' . str_pad((string) ($sharePurchase->id), 4, '0', STR_PAD_LEFT),
            'entry_date' => $sharePurchase->purchase_date,
            'entry_type' => 'share_purchase',
            'description' => "Share purchase by {$userName} ({$sharePurchase->number_of_shares} shares)",
            'reference' => 'SP-' . $sharePurchase->id,
            'total_debit' => $sharePurchase->total_amount,
            'total_credit' => $sharePurchase->total_amount,
            'status' => 'posted',
            'created_by' => Auth::id(),
        ]);

        // Create journal entry lines (double-entry)
        // Debit: Cash/Bank (Asset increases)
        $journalEntry->lines()->create([
            'account_id' => $cashAccount->id,
            'debit_amount' => $sharePurchase->total_amount,
            'credit_amount' => 0,
            'description' => "Share purchase payment from {$userName}",
        ]);

        // Credit: Share Capital (Equity increases)
        $journalEntry->lines()->create([
            'account_id' => $shareCapitalAccount->id,
            'debit_amount' => 0,
            'credit_amount' => $sharePurchase->total_amount,
            'description' => "Share capital contribution from {$userName}",
        ]);

        // Post the journal entry to update account balances
        $journalEntry->post();
    }
}
