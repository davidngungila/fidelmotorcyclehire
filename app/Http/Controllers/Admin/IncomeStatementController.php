<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        // Get operating revenue
        $operatingRevenue = Account::where('account_type', 'revenue')
            ->where('account_subtype', 'operating_revenue')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalOperatingRevenue = $operatingRevenue->sum('current_balance');
        
        // Get non-operating revenue
        $nonOperatingRevenue = Account::where('account_type', 'revenue')
            ->where('account_subtype', 'non_operating_revenue')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalNonOperatingRevenue = $nonOperatingRevenue->sum('current_balance');
        
        // Get operating expenses
        $operatingExpenses = Account::where('account_type', 'expense')
            ->where('account_subtype', 'operating_expense')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalOperatingExpenses = $operatingExpenses->sum('current_balance');
        
        // Get non-operating expenses
        $nonOperatingExpenses = Account::where('account_type', 'expense')
            ->where('account_subtype', 'non_operating_expense')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $totalNonOperatingExpenses = $nonOperatingExpenses->sum('current_balance');
        
        // Calculate totals
        $totalRevenue = $totalOperatingRevenue + $totalNonOperatingRevenue;
        $totalExpenses = $totalOperatingExpenses + $totalNonOperatingExpenses;
        $grossProfit = $totalOperatingRevenue - $totalOperatingExpenses;
        $netIncome = $totalRevenue - $totalExpenses;
        
        return view('admin.income-statement.index', compact(
            'startDate',
            'endDate',
            'operatingRevenue',
            'nonOperatingRevenue',
            'totalOperatingRevenue',
            'totalNonOperatingRevenue',
            'operatingExpenses',
            'nonOperatingExpenses',
            'totalOperatingExpenses',
            'totalNonOperatingExpenses',
            'totalRevenue',
            'totalExpenses',
            'grossProfit',
            'netIncome'
        ));
    }
}
