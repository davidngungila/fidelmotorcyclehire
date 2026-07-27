<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Imports\TransactionsImport;

class TransactionController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('membercode')) {
            $query->byMemberCode($request->membercode);
        }

        if ($request->filled('transaction_type')) {
            $query->byTransactionType($request->transaction_type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(25);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('admin.transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'membercode' => 'required|string|max:50',
            'transaction_type' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
        ]);

        Transaction::create($validated);

        $this->success('Transaction created successfully.');
        return redirect()->route('admin.transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'membercode' => 'required|string|max:50',
            'transaction_type' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
        ]);

        $transaction->update($validated);

        $this->success('Transaction updated successfully.');
        return redirect()->route('admin.transactions.index');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        $this->success('Transaction deleted successfully.');
        return redirect()->route('admin.transactions.index');
    }

    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        Excel::import(new TransactionsImport, $request->file('file'));

        $this->success('Transactions imported successfully.');
        return redirect()->route('admin.transactions.index');
    }
}
