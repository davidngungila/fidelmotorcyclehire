<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function dashboard()
    {
        return view('admin.accounting.dashboard');
    }

    public function chartOfAccounts()
    {
        return view('admin.accounting.chart-of-accounts');
    }

    public function journalEntries()
    {
        return view('admin.accounting.journal-entries');
    }

    public function generalLedger()
    {
        return view('admin.accounting.general-ledger');
    }

    public function trialBalance()
    {
        return view('admin.accounting.trial-balance');
    }

    public function balanceSheet()
    {
        return view('admin.accounting.balance-sheet');
    }

    public function incomeStatement()
    {
        return view('admin.accounting.income-statement');
    }

    public function cashFlow()
    {
        return view('admin.accounting.cash-flow');
    }

    public function fixedAssets()
    {
        return view('admin.accounting.fixed-assets');
    }

    public function depreciation()
    {
        return view('admin.accounting.depreciation');
    }

    public function bankAccounts()
    {
        return view('admin.accounting.bank-accounts');
    }

    public function bankReconciliation()
    {
        return view('admin.accounting.bank-reconciliation');
    }

    public function receipts()
    {
        return view('admin.accounting.receipts');
    }

    public function payments()
    {
        return view('admin.accounting.payments');
    }

    public function expenses()
    {
        return view('admin.accounting.expenses');
    }

    public function revenue()
    {
        return view('admin.accounting.revenue');
    }

    public function accountsReceivable()
    {
        return view('admin.accounting.accounts-receivable');
    }

    public function accountsPayable()
    {
        return view('admin.accounting.accounts-payable');
    }

    public function budgets()
    {
        return view('admin.accounting.budgets');
    }

    public function financialPeriods()
    {
        return view('admin.accounting.financial-periods');
    }

    public function closingEntries()
    {
        return view('admin.accounting.closing-entries');
    }

    public function taxManagement()
    {
        return view('admin.accounting.tax-management');
    }

    public function auditTrail()
    {
        return view('admin.accounting.audit-trail');
    }

    public function financialReports()
    {
        return view('admin.accounting.financial-reports');
    }

    public function settings()
    {
        return view('admin.accounting.settings');
    }
}
