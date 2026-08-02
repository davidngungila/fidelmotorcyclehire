<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $asOfDate = $request->input('as_of_date', now()->format('Y-m-d'));
        
        // Get assets
        $currentAssets = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $fixedAssets = Account::where('account_type', 'asset')
            ->where('account_subtype', 'fixed_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalCurrentAssets = $currentAssets->sum('current_balance');
        $totalFixedAssets = $fixedAssets->sum('current_balance');
        $totalAssets = $totalCurrentAssets + $totalFixedAssets;
        
        // Get liabilities
        $currentLiabilities = Account::where('account_type', 'liability')
            ->where('account_subtype', 'current_liability')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $longTermLiabilities = Account::where('account_type', 'liability')
            ->where('account_subtype', 'long_term_liability')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalCurrentLiabilities = $currentLiabilities->sum('current_balance');
        $totalLongTermLiabilities = $longTermLiabilities->sum('current_balance');
        $totalLiabilities = $totalCurrentLiabilities + $totalLongTermLiabilities;
        
        // Get equity
        $equityAccounts = Account::where('account_type', 'equity')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalEquity = $equityAccounts->sum('current_balance');
        
        // Calculate total liabilities and equity
        $totalLiabilitiesAndEquity = $totalLiabilities + $totalEquity;
        
        return view('admin.balance-sheet.index', compact(
            'asOfDate',
            'currentAssets',
            'fixedAssets',
            'totalCurrentAssets',
            'totalFixedAssets',
            'totalAssets',
            'currentLiabilities',
            'longTermLiabilities',
            'totalCurrentLiabilities',
            'totalLongTermLiabilities',
            'totalLiabilities',
            'equityAccounts',
            'totalEquity',
            'totalLiabilitiesAndEquity'
        ));
    }
}
