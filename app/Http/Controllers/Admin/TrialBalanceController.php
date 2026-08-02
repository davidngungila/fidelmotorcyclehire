<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $asOfDate = $request->input('as_of_date', now()->format('Y-m-d'));
        
        $accounts = Account::where('is_active', true)
            ->where('current_balance', '!=', 0)
            ->orderBy('account_code')
            ->get();
        
        $totalDebit = 0;
        $totalCredit = 0;
        
        foreach ($accounts as $account) {
            if ($account->isDebitAccount()) {
                $totalDebit += $account->current_balance;
            } else {
                $totalCredit += $account->current_balance;
            }
        }
        
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01;
        
        return view('admin.trial-balance.index', compact('accounts', 'totalDebit', 'totalCredit', 'isBalanced', 'asOfDate'));
    }
}
