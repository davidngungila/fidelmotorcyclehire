<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\EncryptedIdService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index()
    {
        $accounts = Account::with('parentAccount')
            ->orderBy('account_code')
            ->get();
        
        return view('admin.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $parentAccounts = Account::where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        return view('admin.accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:20|unique:accounts',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_subtype' => 'nullable|in:current_asset,fixed_asset,loan_receivable,investment,current_liability,long_term_liability,savings_deposit,swf_fund,owners_equity,share_capital,operating_revenue,non_operating_revenue,interest_income,operating_expense,non_operating_expense',
            'description' => 'nullable|string',
            'opening_balance' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'is_system_account' => 'boolean',
            'parent_account_id' => 'nullable|exists:accounts,id',
        ]);

        $validated['current_balance'] = $validated['opening_balance'];
        $validated['level'] = $validated['parent_account_id'] 
            ? (Account::find($validated['parent_account_id'])->level + 1) 
            : 1;

        Account::create($validated);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function show($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Invalid account ID.');
        }

        $account = Account::with(['parentAccount', 'childAccounts', 'journalEntryLines'])
            ->findOrFail($id);
        
        return view('admin.accounts.show', compact('account'));
    }

    public function edit($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Invalid account ID.');
        }

        $account = Account::findOrFail($id);
        $parentAccounts = Account::where('is_active', true)
            ->where('id', '!=', $id)
            ->orderBy('account_code')
            ->get();
        
        return view('admin.accounts.edit', compact('account', 'parentAccounts'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Invalid account ID.');
        }

        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'account_code' => 'required|string|max:20|unique:accounts,account_code,' . $id,
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_subtype' => 'nullable|in:current_asset,fixed_asset,loan_receivable,investment,current_liability,long_term_liability,savings_deposit,swf_fund,owners_equity,share_capital,operating_revenue,non_operating_revenue,interest_income,operating_expense,non_operating_expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'parent_account_id' => 'nullable|exists:accounts,id',
        ]);

        $account->update($validated);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Invalid account ID.');
        }

        $account = Account::findOrFail($id);
        
        if ($account->is_system_account) {
            return back()->with('error', 'Cannot delete system accounts.');
        }

        if ($account->childAccounts()->count() > 0) {
            return back()->with('error', 'Cannot delete account with child accounts.');
        }

        if ($account->journalEntryLines()->count() > 0) {
            return back()->with('error', 'Cannot delete account with transactions.');
        }

        $account->delete();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
