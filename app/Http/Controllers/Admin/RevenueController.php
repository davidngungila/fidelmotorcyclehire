<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\Account;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index()
    {
        $revenues = JournalEntry::where('entry_type', 'revenue')
            ->with(['createdBy', 'lines.account'])
            ->orderBy('entry_date', 'desc')
            ->get();
        
        return view('admin.revenues.index', compact('revenues'));
    }

    public function create()
    {
        $accounts = Account::where('account_type', 'revenue')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $assetAccounts = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        return view('admin.revenues.create', compact('accounts', 'assetAccounts'));
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

        $validated['entry_number'] = 'REV-' . date('Ymd') . '-' . str_pad(JournalEntry::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['entry_type'] = 'revenue';
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

        return redirect()->route('admin.revenues.index')
            ->with('success', 'Revenue created and posted successfully.');
    }

    public function show($id)
    {
        $revenue = JournalEntry::with(['createdBy', 'lines.account'])
            ->where('entry_type', 'revenue')
            ->findOrFail($id);
        
        return view('admin.revenues.show', compact('revenue'));
    }

    public function destroy($id)
    {
        $revenue = JournalEntry::where('entry_type', 'revenue')->findOrFail($id);
        
        if ($revenue->status === 'posted') {
            return back()->with('error', 'Cannot delete posted revenues. Void the revenue first.');
        }

        $revenue->delete();

        return redirect()->route('admin.revenues.index')
            ->with('success', 'Revenue deleted successfully.');
    }
}
