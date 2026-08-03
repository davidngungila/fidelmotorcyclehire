<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Services\EncryptedIdService;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index()
    {
        $journalEntries = JournalEntry::with(['createdBy', 'financialPeriod'])
            ->orderBy('entry_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.journal-entries.index', compact('journalEntries'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $financialPeriods = \App\Models\FinancialPeriod::where('status', 'open')->get();
        
        return view('admin.journal-entries.create', compact('accounts', 'financialPeriods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'reference' => 'nullable|string',
            'entry_type' => 'required|in:manual,automatic,adjusting,closing,loan_disbursement,loan_repayment,investment,share_purchase,swf_contribution,deposit',
            'financial_period_id' => 'nullable|exists:financial_periods,id',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.debit_amount' => 'required|numeric|min:0',
            'lines.*.credit_amount' => 'required|numeric|min:0',
        ]);

        // Calculate totals
        $totalDebit = 0;
        $totalCredit = 0;
        
        foreach ($validated['lines'] as $line) {
            $totalDebit += $line['debit_amount'];
            $totalCredit += $line['credit_amount'];
        }

        // Validate double-entry
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()
                ->with('error', 'Journal entry must be balanced. Total debit must equal total credit.');
        }

        $validated['total_debit'] = $totalDebit;
        $validated['total_credit'] = $totalCredit;
        $validated['status'] = 'draft';
        $validated['created_by'] = auth()->id();
        $validated['entry_number'] = 'JE-' . date('Ymd') . '-' . str_pad(JournalEntry::count() + 1, 4, '0', STR_PAD_LEFT);

        $journalEntry = JournalEntry::create($validated);

        // Create journal entry lines
        foreach ($validated['lines'] as $lineData) {
            JournalEntryLine::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $lineData['account_id'],
                'description' => $lineData['description'] ?? $validated['description'],
                'debit_amount' => $lineData['debit_amount'],
                'credit_amount' => $lineData['credit_amount'],
            ]);
        }

        return redirect()->route('admin.journal-entries.index')
            ->with('success', 'Journal entry created successfully.');
    }

    public function show($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.journal-entries.index')
                ->with('error', 'Invalid journal entry ID.');
        }

        $journalEntry = JournalEntry::with(['lines.account', 'createdBy', 'approvedBy', 'financialPeriod'])
            ->findOrFail($id);
        
        return view('admin.journal-entries.show', compact('journalEntry'));
    }

    public function edit($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.journal-entries.index')
                ->with('error', 'Invalid journal entry ID.');
        }

        $journalEntry = JournalEntry::with('lines')->findOrFail($id);
        
        if ($journalEntry->status === 'posted') {
            return back()->with('error', 'Cannot edit posted journal entries.');
        }

        $accounts = Account::where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $financialPeriods = \App\Models\FinancialPeriod::where('status', 'open')->get();
        
        return view('admin.journal-entries.edit', compact('journalEntry', 'accounts', 'financialPeriods'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.journal-entries.index')
                ->with('error', 'Invalid journal entry ID.');
        }

        $journalEntry = JournalEntry::findOrFail($id);
        
        if ($journalEntry->status === 'posted') {
            return back()->with('error', 'Cannot edit posted journal entries.');
        }
        
        if ($journalEntry->status === 'voided') {
            return back()->with('error', 'Cannot edit voided journal entries.');
        }

        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'reference' => 'nullable|string',
            'entry_type' => 'required|in:manual,automatic,adjusting,closing,loan_disbursement,loan_repayment,investment,share_purchase,swf_contribution,deposit',
            'financial_period_id' => 'nullable|exists:financial_periods,id',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.debit_amount' => 'required|numeric|min:0',
            'lines.*.credit_amount' => 'required|numeric|min:0',
        ]);

        // Calculate totals
        $totalDebit = 0;
        $totalCredit = 0;
        
        foreach ($validated['lines'] as $line) {
            $totalDebit += $line['debit_amount'];
            $totalCredit += $line['credit_amount'];
        }

        // Validate double-entry
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()
                ->with('error', 'Journal entry must be balanced. Total debit must equal total credit.');
        }

        $validated['total_debit'] = $totalDebit;
        $validated['total_credit'] = $totalCredit;

        $journalEntry->update($validated);

        // Delete existing lines
        $journalEntry->lines()->delete();

        // Create new lines
        foreach ($validated['lines'] as $lineData) {
            JournalEntryLine::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $lineData['account_id'],
                'description' => $lineData['description'] ?? $validated['description'],
                'debit_amount' => $lineData['debit_amount'],
                'credit_amount' => $lineData['credit_amount'],
            ]);
        }

        return redirect()->route('admin.journal-entries.index')
            ->with('success', 'Journal entry updated successfully.');
    }

    public function destroy($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.journal-entries.index')
                ->with('error', 'Invalid journal entry ID.');
        }

        $journalEntry = JournalEntry::findOrFail($id);
        
        if ($journalEntry->status === 'posted') {
            return back()->with('error', 'Cannot delete posted journal entries.');
        }

        $journalEntry->delete();

        return redirect()->route('admin.journal-entries.index')
            ->with('success', 'Journal entry deleted successfully.');
    }

    public function post($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.journal-entries.index')
                ->with('error', 'Invalid journal entry ID.');
        }

        $journalEntry = JournalEntry::findOrFail($id);
        
        if ($journalEntry->status === 'posted') {
            return back()->with('error', 'Journal entry is already posted.');
        }

        if ($journalEntry->status === 'voided') {
            return back()->with('error', 'Cannot post voided journal entries.');
        }

        try {
            $journalEntry->post();
            return back()->with('success', 'Journal entry posted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function void($encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.journal-entries.index')
                ->with('error', 'Invalid journal entry ID.');
        }

        $journalEntry = JournalEntry::findOrFail($id);
        
        if ($journalEntry->status === 'voided') {
            return back()->with('error', 'Journal entry is already voided.');
        }

        if ($journalEntry->status === 'posted') {
            return back()->with('error', 'Cannot void posted journal entries. Create a reversing entry instead.');
        }

        $journalEntry->status = 'voided';
        $journalEntry->save();

        return back()->with('success', 'Journal entry voided successfully.');
    }
}
