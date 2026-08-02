<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        // Cash flow from operating activities
        $operatingInflows = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $operatingOutflows = Account::where('account_type', 'liability')
            ->where('account_subtype', 'current_liability')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalOperatingInflows = $operatingInflows->sum('current_balance');
        $totalOperatingOutflows = $operatingOutflows->sum('current_balance');
        $netOperatingCashFlow = $totalOperatingInflows - $totalOperatingOutflows;
        
        // Cash flow from investing activities
        $investingInflows = Account::where('account_type', 'asset')
            ->where('account_subtype', 'fixed_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalInvestingInflows = $investingInflows->sum('current_balance');
        
        // Cash flow from financing activities
        $financingInflows = Account::where('account_type', 'liability')
            ->where('account_subtype', 'long_term_liability')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $equityAccounts = Account::where('account_type', 'equity')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalFinancingInflows = $financingInflows->sum('current_balance');
        $totalEquity = $equityAccounts->sum('current_balance');
        $netFinancingCashFlow = $totalFinancingInflows + $totalEquity;
        
        // Net cash flow
        $netCashFlow = $netOperatingCashFlow + $totalInvestingInflows + $netFinancingCashFlow;
        
        return view('admin.cash-flow.index', compact(
            'startDate',
            'endDate',
            'operatingInflows',
            'operatingOutflows',
            'totalOperatingInflows',
            'totalOperatingOutflows',
            'netOperatingCashFlow',
            'investingInflows',
            'totalInvestingInflows',
            'financingInflows',
            'equityAccounts',
            'totalFinancingInflows',
            'totalEquity',
            'netFinancingCashFlow',
            'netCashFlow'
        ));
    }
}
