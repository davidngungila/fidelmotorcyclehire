<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Account;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::with(['account', 'relatedAccount'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.bank-accounts.index', compact('bankAccounts'));
    }

    public function create()
    {
        $accounts = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        return view('admin.bank-accounts.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts',
            'account_type' => 'required|in:checking,savings,investment',
            'currency' => 'required|string|max:3',
            'opening_balance' => 'required|numeric|min:0',
            'current_balance' => 'required|numeric|min:0',
            'branch_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:11',
            'iban' => 'nullable|string|max:34',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        BankAccount::create($validated);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account created successfully.');
    }

    public function show($id)
    {
        $bankAccount = BankAccount::with(['account', 'relatedAccount'])->findOrFail($id);
        
        return view('admin.bank-accounts.show', compact('bankAccount'));
    }

    public function edit($id)
    {
        $bankAccount = BankAccount::findOrFail($id);
        $accounts = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        return view('admin.bank-accounts.edit', compact('bankAccount', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number,' . $id,
            'account_type' => 'required|in:checking,savings,investment',
            'currency' => 'required|string|max:3',
            'opening_balance' => 'required|numeric|min:0',
            'current_balance' => 'required|numeric|min:0',
            'branch_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:11',
            'iban' => 'nullable|string|max:34',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $bankAccount->update($validated);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account updated successfully.');
    }

    public function destroy($id)
    {
        $bankAccount = BankAccount::findOrFail($id);
        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Bank account deleted successfully.');
    }
}
