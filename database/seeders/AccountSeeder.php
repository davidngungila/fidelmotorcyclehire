<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assets
        $cash = Account::create([
            'account_code' => '1000',
            'account_name' => 'Assets',
            'account_type' => 'asset',
            'account_subtype' => null,
            'description' => 'Total Assets',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'level' => 1,
        ]);

        Account::create([
            'account_code' => '1100',
            'account_name' => 'Current Assets',
            'account_type' => 'asset',
            'account_subtype' => 'current_asset',
            'description' => 'Current Assets',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $cash->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '1110',
            'account_name' => 'Cash on Hand',
            'account_type' => 'asset',
            'account_subtype' => 'current_asset',
            'description' => 'Physical cash held by the SACCO',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1120',
            'account_name' => 'Cash at Bank',
            'account_type' => 'asset',
            'account_subtype' => 'current_asset',
            'description' => 'Funds held in bank accounts',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1130',
            'account_name' => 'Loans Receivable',
            'account_type' => 'asset',
            'account_subtype' => 'current_asset',
            'description' => 'Loans issued to members',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1140',
            'account_name' => 'Savings Deposits Receivable',
            'account_type' => 'asset',
            'account_subtype' => 'current_asset',
            'description' => 'Member savings deposits',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1150',
            'account_name' => 'Share Capital Receivable',
            'account_type' => 'asset',
            'account_subtype' => 'current_asset',
            'description' => 'Member share capital',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1160',
            'account_name' => 'Interest Receivable',
            'account_type' => 'asset',
            'account_subtype' => 'current_asset',
            'description' => 'Interest income not yet received',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1200',
            'account_name' => 'Fixed Assets',
            'account_type' => 'asset',
            'account_subtype' => 'fixed_asset',
            'description' => 'Fixed Assets',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $cash->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '1210',
            'account_name' => 'Land and Buildings',
            'account_type' => 'asset',
            'account_subtype' => 'fixed_asset',
            'description' => 'Land and buildings owned by SACCO',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1220',
            'account_name' => 'Furniture and Equipment',
            'account_type' => 'asset',
            'account_subtype' => 'fixed_asset',
            'description' => 'Office furniture and equipment',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1230',
            'account_name' => 'Computer Equipment',
            'account_type' => 'asset',
            'account_subtype' => 'fixed_asset',
            'description' => 'Computers and IT equipment',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '1240',
            'account_name' => 'Motor Vehicles',
            'account_type' => 'asset',
            'account_subtype' => 'fixed_asset',
            'description' => 'Vehicles owned by SACCO',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $cash->id,
            'level' => 3,
        ]);

        // Liabilities
        $liabilities = Account::create([
            'account_code' => '2000',
            'account_name' => 'Liabilities',
            'account_type' => 'liability',
            'account_subtype' => null,
            'description' => 'Total Liabilities',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'level' => 1,
        ]);

        Account::create([
            'account_code' => '2100',
            'account_name' => 'Current Liabilities',
            'account_type' => 'liability',
            'account_subtype' => 'current_liability',
            'description' => 'Current Liabilities',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $liabilities->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '2110',
            'account_name' => 'Savings Deposits Payable',
            'account_type' => 'liability',
            'account_subtype' => 'current_liability',
            'description' => 'Member savings deposits owed',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $liabilities->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '2120',
            'account_name' => 'Share Capital Payable',
            'account_type' => 'liability',
            'account_subtype' => 'current_liability',
            'description' => 'Member share capital owed',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $liabilities->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '2130',
            'account_name' => 'Loans Payable',
            'account_type' => 'liability',
            'account_subtype' => 'current_liability',
            'description' => 'Loans from financial institutions',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $liabilities->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '2140',
            'account_name' => 'Interest Payable',
            'account_type' => 'liability',
            'account_subtype' => 'current_liability',
            'description' => 'Interest owed on loans',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $liabilities->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '2150',
            'account_name' => 'Accounts Payable',
            'account_type' => 'liability',
            'account_subtype' => 'current_liability',
            'description' => 'Money owed to suppliers',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $liabilities->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '2160',
            'account_name' => 'Dividends Payable',
            'account_type' => 'liability',
            'account_subtype' => 'current_liability',
            'description' => 'Dividends declared but not paid',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $liabilities->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '2200',
            'account_name' => 'Long Term Liabilities',
            'account_type' => 'liability',
            'account_subtype' => 'long_term_liability',
            'description' => 'Long Term Liabilities',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $liabilities->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '2210',
            'account_name' => 'Long Term Loans',
            'account_type' => 'liability',
            'account_subtype' => 'long_term_liability',
            'description' => 'Long term loans from financial institutions',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $liabilities->id,
            'level' => 3,
        ]);

        // Equity
        $equity = Account::create([
            'account_code' => '3000',
            'account_name' => 'Equity',
            'account_type' => 'equity',
            'account_subtype' => null,
            'description' => 'Total Equity',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'level' => 1,
        ]);

        Account::create([
            'account_code' => '3100',
            'account_name' => 'Member Equity',
            'account_type' => 'equity',
            'account_subtype' => 'owners_equity',
            'description' => 'Total member equity',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $equity->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '3200',
            'account_name' => 'Retained Earnings',
            'account_type' => 'equity',
            'account_subtype' => 'owners_equity',
            'description' => 'Accumulated retained earnings',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $equity->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '3300',
            'account_name' => 'Reserve Fund',
            'account_type' => 'equity',
            'account_subtype' => 'owners_equity',
            'description' => 'Statutory reserve fund',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $equity->id,
            'level' => 2,
        ]);

        // Revenue
        $revenue = Account::create([
            'account_code' => '4000',
            'account_name' => 'Revenue',
            'account_type' => 'revenue',
            'account_subtype' => null,
            'description' => 'Total Revenue',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'level' => 1,
        ]);

        Account::create([
            'account_code' => '4100',
            'account_name' => 'Operating Revenue',
            'account_type' => 'revenue',
            'account_subtype' => 'operating_revenue',
            'description' => 'Operating Revenue',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $revenue->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '4110',
            'account_name' => 'Interest Income from Loans',
            'account_type' => 'revenue',
            'account_subtype' => 'operating_revenue',
            'description' => 'Interest earned from member loans',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $revenue->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '4120',
            'account_name' => 'Loan Processing Fees',
            'account_type' => 'revenue',
            'account_subtype' => 'operating_revenue',
            'description' => 'Fees charged for loan processing',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $revenue->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '4130',
            'account_name' => 'Late Payment Fees',
            'account_type' => 'revenue',
            'account_subtype' => 'operating_revenue',
            'description' => 'Fees charged for late payments',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $revenue->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '4140',
            'account_name' => 'Membership Fees',
            'account_type' => 'revenue',
            'account_subtype' => 'operating_revenue',
            'description' => 'Annual membership fees',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $revenue->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '4200',
            'account_name' => 'Non-Operating Revenue',
            'account_type' => 'revenue',
            'account_subtype' => 'non_operating_revenue',
            'description' => 'Non-Operating Revenue',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $revenue->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '4210',
            'account_name' => 'Interest Income from Investments',
            'account_type' => 'revenue',
            'account_subtype' => 'non_operating_revenue',
            'description' => 'Interest earned from investments',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $revenue->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '4220',
            'account_name' => 'Bank Interest Income',
            'account_type' => 'revenue',
            'account_subtype' => 'non_operating_revenue',
            'description' => 'Interest earned from bank deposits',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $revenue->id,
            'level' => 3,
        ]);

        // Expenses
        $expenses = Account::create([
            'account_code' => '5000',
            'account_name' => 'Expenses',
            'account_type' => 'expense',
            'account_subtype' => null,
            'description' => 'Total Expenses',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'level' => 1,
        ]);

        Account::create([
            'account_code' => '5100',
            'account_name' => 'Operating Expenses',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Operating Expenses',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $expenses->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '5110',
            'account_name' => 'Interest Expense on Deposits',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Interest paid on member deposits',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5120',
            'account_name' => 'Interest Expense on Loans',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Interest paid on borrowed funds',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5130',
            'account_name' => 'Salaries and Wages',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Staff salaries and wages',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5140',
            'account_name' => 'Rent and Utilities',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Office rent and utility bills',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5150',
            'account_name' => 'Office Supplies',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Office supplies and consumables',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5160',
            'account_name' => 'Travel and Transportation',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Travel and transportation expenses',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5170',
            'account_name' => 'Communication Expenses',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Phone, internet, and communication costs',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5180',
            'account_name' => 'Depreciation Expense',
            'account_type' => 'expense',
            'account_subtype' => 'operating_expense',
            'description' => 'Depreciation of fixed assets',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);

        Account::create([
            'account_code' => '5200',
            'account_name' => 'Non-Operating Expenses',
            'account_type' => 'expense',
            'account_subtype' => 'non_operating_expense',
            'description' => 'Non-Operating Expenses',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => true,
            'parent_account_id' => $expenses->id,
            'level' => 2,
        ]);

        Account::create([
            'account_code' => '5210',
            'account_name' => 'Loss on Sale of Assets',
            'account_type' => 'expense',
            'account_subtype' => 'non_operating_expense',
            'description' => 'Losses from asset disposals',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => true,
            'is_system_account' => false,
            'parent_account_id' => $expenses->id,
            'level' => 3,
        ]);
    }
}
