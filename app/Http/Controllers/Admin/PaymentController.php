<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\Account;
use App\Models\Member;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = JournalEntry::where('entry_type', 'payment')
            ->with(['createdBy', 'lines.account'])
            ->orderBy('entry_date', 'desc')
            ->get();
        
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $members = Member::orderBy('name')->get();
        
        return view('admin.payments.create', compact('accounts', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'reference' => 'nullable|string',
            'member_id' => 'nullable|exists:members,id',
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

        $validated['entry_number'] = 'PAY-' . date('Ymd') . '-' . str_pad(JournalEntry::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['entry_type'] = 'payment';
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
                'member_id' => $validated['member_id'] ?? null,
            ]);
        }

        $journalEntry->post();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment created and posted successfully.');
    }

    public function show($id)
    {
        $payment = JournalEntry::with(['createdBy', 'lines.account', 'lines.member'])
            ->where('entry_type', 'payment')
            ->findOrFail($id);
        
        return view('admin.payments.show', compact('payment'));
    }

    public function destroy($id)
    {
        $payment = JournalEntry::where('entry_type', 'payment')->findOrFail($id);
        
        if ($payment->status === 'posted') {
            return back()->with('error', 'Cannot delete posted payments. Void the payment first.');
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
