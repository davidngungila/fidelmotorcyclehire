<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\Account;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = JournalEntry::where('entry_type', 'expense')
            ->with(['createdBy', 'lines.account'])
            ->orderBy('entry_date', 'desc')
            ->get();
        
        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts = Account::where('account_type', 'expense')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $assetAccounts = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        return view('admin.expenses.create', compact('accounts', 'assetAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'reference' => 'nullable|string',
            'category' => 'required|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit_amount' => 'required|numeric|min:0',
            'lines.*.credit_amount' => 'required|numeric|min:0',
            'lines.*.description' => 'nullable|string',
        ]);

        $totalDebit = collect($validated['lines'])->sum('debit_amount');
        $totalCredit = collect($validated['lines'])->sum('credit_amount');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()->withErrors(['balance' => 'Total debits must equal total credits']);
        }

        $validated['entry_number'] = 'EXP-' . date('Ymd') . '-' . str_pad(JournalEntry::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['entry_type'] = 'expense';
        $validated['status'] = 'posted';
        $validated['total_debit'] = $totalDebit;
        $validated['total_credit'] = $totalCredit;
        $validated['created_by'] = auth()->id();

        $journalEntry = JournalEntry::create($validated);

        foreach ($validated['lines'] as $line) {
            $journalEntry->lines()->create([
                'account_id' => $line['account_id'],
                'debit_amount' => $line['debit_amount'],
                'credit_amount' => $line['credit_amount'],
                'description' => $line['description'] ?? $validated['description'],
            ]);
        }

        $journalEntry->post();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense created and posted successfully.');
    }

    public function show($id)
    {
        $expense = JournalEntry::with(['createdBy', 'lines.account'])
            ->where('entry_type', 'expense')
            ->findOrFail($id);
        
        return view('admin.expenses.show', compact('expense'));
    }

    public function destroy($id)
    {
        $expense = JournalEntry::where('entry_type', 'expense')->findOrFail($id);
        
        if ($expense->status === 'posted') {
            return back()->with('error', 'Cannot delete posted expenses. Void the expense first.');
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
