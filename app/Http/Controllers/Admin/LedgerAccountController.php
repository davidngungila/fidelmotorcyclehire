<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\LedgerAccount;
use Illuminate\Http\Request;

class LedgerAccountController extends Controller
{
    public function index()
    {
        $accounts = Account::where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        return view('admin.ledger.index', compact('accounts'));
    }

    public function show($accountId)
    {
        $account = Account::findOrFail($accountId);
        
        $ledgerEntries = LedgerAccount::where('account_id', $accountId)
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();
        
        return view('admin.ledger.show', compact('account', 'ledgerEntries'));
    }

    public function accountLedger(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $account = Account::findOrFail($validated['account_id']);
        
        $query = LedgerAccount::where('account_id', $validated['account_id']);
        
        if (isset($validated['start_date'])) {
            $query->where('transaction_date', '>=', $validated['start_date']);
        }
        
        if (isset($validated['end_date'])) {
            $query->where('transaction_date', '<=', $validated['end_date']);
        }
        
        $ledgerEntries = $query->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();
        
        return view('admin.ledger.show', compact('account', 'ledgerEntries'));
    }
}
