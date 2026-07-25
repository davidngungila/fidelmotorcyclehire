<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\GoogleSheetRepositoryInterface;

class MockGoogleSheetRepository implements GoogleSheetRepositoryInterface
{
    protected array $members = [
        ['name' => 'John Kamau', 'gender' => 'Male', 'phone' => '+254711000001', 'email' => 'john.kamau@example.co.ke', 'address' => 'P.O. Box 1234, Nairobi', 'occupation' => 'Accountant', 'employer' => 'Kenya Revenue Authority', 'branch' => 'Nairobi', 'registration_date' => '2023-01-15', 'status' => 'Active', 'member_number' => 'M001'],
        ['name' => 'Jane Wanjiru', 'gender' => 'Female', 'phone' => '+254711000002', 'email' => 'jane.wanjiru@example.co.ke', 'address' => 'P.O. Box 5678, Mombasa', 'occupation' => 'Teacher', 'employer' => 'Mombasa County Govt', 'branch' => 'Mombasa', 'registration_date' => '2023-02-20', 'status' => 'Active', 'member_number' => 'M002'],
        ['name' => 'Peter Otieno', 'gender' => 'Male', 'phone' => '+254711000003', 'email' => 'peter.otieno@example.co.ke', 'address' => 'P.O. Box 9012, Kisumu', 'occupation' => 'Engineer', 'employer' => 'Kenya Power', 'branch' => 'Kisumu', 'registration_date' => '2023-03-10', 'status' => 'Active', 'member_number' => 'M003'],
        ['name' => 'Mary Akinyi', 'gender' => 'Female', 'phone' => '+254711000004', 'email' => 'mary.akinyi@example.co.ke', 'address' => 'P.O. Box 3456, Nakuru', 'occupation' => 'Nurse', 'employer' => 'Nakuru Referral Hospital', 'branch' => 'Nakuru', 'registration_date' => '2023-04-05', 'status' => 'Active', 'member_number' => 'M004'],
        ['name' => 'David Kiprop', 'gender' => 'Male', 'phone' => '+254711000005', 'email' => 'david.kiprop@example.co.ke', 'address' => 'P.O. Box 7890, Eldoret', 'occupation' => 'Farmer', 'employer' => 'Self-Employed', 'branch' => 'Eldoret', 'registration_date' => '2023-05-18', 'status' => 'Active', 'member_number' => 'M005'],
        ['name' => 'Grace Njeri', 'gender' => 'Female', 'phone' => '+254711000006', 'email' => 'grace.njeri@example.co.ke', 'address' => 'P.O. Box 2345, Thika', 'occupation' => 'Businesswoman', 'employer' => 'Self-Employed', 'branch' => 'Thika', 'registration_date' => '2023-06-22', 'status' => 'Dormant', 'member_number' => 'M006'],
        ['name' => 'James Omondi', 'gender' => 'Male', 'phone' => '+254711000007', 'email' => 'james.omondi@example.co.ke', 'address' => 'P.O. Box 6789, Nairobi', 'occupation' => 'IT Specialist', 'employer' => 'Safaricom PLC', 'branch' => 'Nairobi', 'registration_date' => '2023-07-30', 'status' => 'Active', 'member_number' => 'M007'],
        ['name' => 'Faith Chebet', 'gender' => 'Female', 'phone' => '+254711000008', 'email' => 'faith.chebet@example.co.ke', 'address' => 'P.O. Box 1122, Eldoret', 'occupation' => 'Lawyer', 'employer' => 'Chebet & Associates', 'branch' => 'Eldoret', 'registration_date' => '2023-08-14', 'status' => 'Active', 'member_number' => 'M008'],
        ['name' => 'Samuel Kariuki', 'gender' => 'Male', 'phone' => '+254711000009', 'email' => 'samuel.kariuki@example.co.ke', 'address' => 'P.O. Box 4455, Nyeri', 'occupation' => 'Civil Servant', 'employer' => 'Ministry of Education', 'branch' => 'Nyeri', 'registration_date' => '2023-09-25', 'status' => 'Active', 'member_number' => 'M009'],
        ['name' => 'Elizabeth Wairimu', 'gender' => 'Female', 'phone' => '+254711000010', 'email' => 'elizabeth.wairimu@example.co.ke', 'address' => 'P.O. Box 8877, Nairobi', 'occupation' => 'Banker', 'employer' => 'Equity Bank', 'branch' => 'Nairobi', 'registration_date' => '2023-10-01', 'status' => 'Pending', 'member_number' => 'M010'],
    ];

    protected const LOANS = [
        ['loan_number' => 'LN-001', 'loan_product' => 'Business Loan', 'loan_amount' => 500000, 'outstanding_balance' => 320000, 'paid_amount' => 180000, 'interest_rate' => 14.5, 'installment' => 28500, 'status' => 'Active', 'disbursement_date' => '2024-01-10', 'maturity_date' => '2026-01-10', 'member_number' => 'M001'],
        ['loan_number' => 'LN-002', 'loan_product' => 'Emergency Loan', 'loan_amount' => 50000, 'outstanding_balance' => 15000, 'paid_amount' => 35000, 'interest_rate' => 12.0, 'installment' => 5200, 'status' => 'Active', 'disbursement_date' => '2024-06-01', 'maturity_date' => '2025-06-01', 'member_number' => 'M001'],
        ['loan_number' => 'LN-003', 'loan_product' => 'Development Loan', 'loan_amount' => 800000, 'outstanding_balance' => 650000, 'paid_amount' => 150000, 'interest_rate' => 13.5, 'installment' => 45000, 'status' => 'Active', 'disbursement_date' => '2024-03-15', 'maturity_date' => '2027-03-15', 'member_number' => 'M002'],
        ['loan_number' => 'LN-004', 'loan_product' => 'School Fees Loan', 'loan_amount' => 120000, 'outstanding_balance' => 0, 'paid_amount' => 120000, 'interest_rate' => 10.0, 'installment' => 13200, 'status' => 'Settled', 'disbursement_date' => '2023-05-01', 'maturity_date' => '2024-05-01', 'member_number' => 'M002'],
        ['loan_number' => 'LN-005', 'loan_product' => 'Agricultural Loan', 'loan_amount' => 300000, 'outstanding_balance' => 210000, 'paid_amount' => 90000, 'interest_rate' => 11.0, 'installment' => 18000, 'status' => 'Active', 'disbursement_date' => '2024-02-20', 'maturity_date' => '2026-02-20', 'member_number' => 'M003'],
        ['loan_number' => 'LN-006', 'loan_product' => 'Personal Loan', 'loan_amount' => 200000, 'outstanding_balance' => 140000, 'paid_amount' => 60000, 'interest_rate' => 15.0, 'installment' => 23000, 'status' => 'Active', 'disbursement_date' => '2024-04-10', 'maturity_date' => '2025-10-10', 'member_number' => 'M004'],
        ['loan_number' => 'LN-007', 'loan_product' => 'Business Loan', 'loan_amount' => 1500000, 'outstanding_balance' => 1200000, 'paid_amount' => 300000, 'interest_rate' => 14.0, 'installment' => 85000, 'status' => 'Active', 'disbursement_date' => '2024-05-20', 'maturity_date' => '2027-05-20', 'member_number' => 'M005'],
        ['loan_number' => 'LN-008', 'loan_product' => 'Emergency Loan', 'loan_amount' => 80000, 'outstanding_balance' => 80000, 'paid_amount' => 0, 'interest_rate' => 12.0, 'installment' => 8300, 'status' => 'Defaulted', 'disbursement_date' => '2023-11-01', 'maturity_date' => '2024-11-01', 'member_number' => 'M006'],
        ['loan_number' => 'LN-009', 'loan_product' => 'Mortgage Loan', 'loan_amount' => 4000000, 'outstanding_balance' => 3800000, 'paid_amount' => 200000, 'interest_rate' => 12.5, 'installment' => 95000, 'status' => 'Active', 'disbursement_date' => '2024-01-01', 'maturity_date' => '2034-01-01', 'member_number' => 'M007'],
        ['loan_number' => 'LN-010', 'loan_product' => 'Development Loan', 'loan_amount' => 600000, 'outstanding_balance' => 480000, 'paid_amount' => 120000, 'interest_rate' => 13.0, 'installment' => 34000, 'status' => 'Active', 'disbursement_date' => '2024-06-15', 'maturity_date' => '2026-12-15', 'member_number' => 'M008'],
        ['loan_number' => 'LN-011', 'loan_product' => 'School Fees Loan', 'loan_amount' => 80000, 'outstanding_balance' => 50000, 'paid_amount' => 30000, 'interest_rate' => 10.0, 'installment' => 8800, 'status' => 'Active', 'disbursement_date' => '2024-07-01', 'maturity_date' => '2025-07-01', 'member_number' => 'M009'],
        ['loan_number' => 'LN-012', 'loan_product' => 'Personal Loan', 'loan_amount' => 150000, 'outstanding_balance' => 150000, 'paid_amount' => 0, 'interest_rate' => 15.0, 'installment' => 17200, 'status' => 'Pending', 'disbursement_date' => '2024-10-05', 'maturity_date' => '2025-10-05', 'member_number' => 'M010'],
    ];

    protected const SAVINGS = [
        'M001' => ['balance' => 245000, 'interest_earned' => 12400, 'running_balance' => 257400, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 30000, 'description' => 'Monthly Savings', 'balance_after' => 245000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 30000, 'description' => 'Monthly Savings', 'balance_after' => 215000],
            ['date' => '2024-08-01', 'type' => 'Deposit', 'amount' => 30000, 'description' => 'Monthly Savings', 'balance_after' => 185000],
            ['date' => '2024-07-15', 'type' => 'Withdrawal', 'amount' => -20000, 'description' => 'Emergency', 'balance_after' => 155000],
            ['date' => '2024-07-01', 'type' => 'Deposit', 'amount' => 30000, 'description' => 'Monthly Savings', 'balance_after' => 175000],
            ['date' => '2024-06-01', 'type' => 'Interest', 'amount' => 3400, 'description' => 'Q2 Interest', 'balance_after' => 148400],
        ], 'member_number' => 'M001'],
        'M002' => ['balance' => 380000, 'interest_earned' => 18500, 'running_balance' => 398500, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 50000, 'description' => 'Monthly Savings', 'balance_after' => 380000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 50000, 'description' => 'Monthly Savings', 'balance_after' => 330000],
            ['date' => '2024-08-01', 'type' => 'Deposit', 'amount' => 50000, 'description' => 'Monthly Savings', 'balance_after' => 280000],
            ['date' => '2024-06-01', 'type' => 'Interest', 'amount' => 5200, 'description' => 'Q2 Interest', 'balance_after' => 235200],
        ], 'member_number' => 'M002'],
        'M003' => ['balance' => 156000, 'interest_earned' => 7800, 'running_balance' => 163800, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 20000, 'description' => 'Monthly Savings', 'balance_after' => 156000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 20000, 'description' => 'Monthly Savings', 'balance_after' => 136000],
            ['date' => '2024-06-01', 'type' => 'Interest', 'amount' => 2400, 'description' => 'Q2 Interest', 'balance_after' => 118400],
        ], 'member_number' => 'M003'],
        'M004' => ['balance' => 92000, 'interest_earned' => 4200, 'running_balance' => 96200, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 15000, 'description' => 'Monthly Savings', 'balance_after' => 92000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 15000, 'description' => 'Monthly Savings', 'balance_after' => 77000],
            ['date' => '2024-08-10', 'type' => 'Withdrawal', 'amount' => -10000, 'description' => 'Medical Bill', 'balance_after' => 62000],
        ], 'member_number' => 'M004'],
        'M005' => ['balance' => 520000, 'interest_earned' => 28000, 'running_balance' => 548000, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 80000, 'description' => 'Farm Proceeds', 'balance_after' => 520000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 60000, 'description' => 'Monthly Savings', 'balance_after' => 440000],
            ['date' => '2024-06-01', 'type' => 'Interest', 'amount' => 8500, 'description' => 'Q2 Interest', 'balance_after' => 388500],
        ], 'member_number' => 'M005'],
        'M006' => ['balance' => 45000, 'interest_earned' => 1800, 'running_balance' => 46800, 'transactions' => [
            ['date' => '2024-05-01', 'type' => 'Deposit', 'amount' => 15000, 'description' => 'Last Deposit', 'balance_after' => 45000],
            ['date' => '2024-06-01', 'type' => 'Interest', 'amount' => 800, 'description' => 'Q2 Interest', 'balance_after' => 45800],
        ], 'member_number' => 'M006'],
        'M007' => ['balance' => 680000, 'interest_earned' => 35000, 'running_balance' => 715000, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 70000, 'description' => 'Monthly Savings', 'balance_after' => 680000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 70000, 'description' => 'Monthly Savings', 'balance_after' => 610000],
            ['date' => '2024-06-01', 'type' => 'Interest', 'amount' => 12500, 'description' => 'Q2 Interest', 'balance_after' => 552500],
        ], 'member_number' => 'M007'],
        'M008' => ['balance' => 420000, 'interest_earned' => 20000, 'running_balance' => 440000, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 45000, 'description' => 'Monthly Savings', 'balance_after' => 420000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 45000, 'description' => 'Monthly Savings', 'balance_after' => 375000],
        ], 'member_number' => 'M008'],
        'M009' => ['balance' => 180000, 'interest_earned' => 9000, 'running_balance' => 189000, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 25000, 'description' => 'Monthly Savings', 'balance_after' => 180000],
            ['date' => '2024-09-01', 'type' => 'Deposit', 'amount' => 25000, 'description' => 'Monthly Savings', 'balance_after' => 155000],
        ], 'member_number' => 'M009'],
        'M010' => ['balance' => 30000, 'interest_earned' => 200, 'running_balance' => 30200, 'transactions' => [
            ['date' => '2024-10-01', 'type' => 'Deposit', 'amount' => 30000, 'description' => 'Registration Deposit', 'balance_after' => 30000],
        ], 'member_number' => 'M010'],
    ];

    protected const DEPOSITS = [
        'M001' => [
            ['certificate_number' => 'FD-001', 'product' => 'Fixed Deposit - 1 Year', 'amount' => 200000, 'interest' => 22000, 'start_date' => '2024-03-01', 'maturity_date' => '2025-03-01', 'status' => 'Active', 'current_value' => 215000, 'member_number' => 'M001'],
            ['certificate_number' => 'FD-002', 'product' => 'Fixed Deposit - 2 Years', 'amount' => 300000, 'interest' => 75000, 'start_date' => '2024-01-15', 'maturity_date' => '2026-01-15', 'status' => 'Active', 'current_value' => 335000, 'member_number' => 'M001'],
        ],
        'M002' => [
            ['certificate_number' => 'FD-003', 'product' => 'Fixed Deposit - 6 Months', 'amount' => 100000, 'interest' => 6000, 'start_date' => '2024-07-01', 'maturity_date' => '2025-01-01', 'status' => 'Active', 'current_value' => 104000, 'member_number' => 'M002'],
        ],
        'M003' => [
            ['certificate_number' => 'FD-004', 'product' => 'Fixed Deposit - 1 Year', 'amount' => 150000, 'interest' => 16500, 'start_date' => '2024-05-10', 'maturity_date' => '2025-05-10', 'status' => 'Active', 'current_value' => 158000, 'member_number' => 'M003'],
        ],
        'M004' => [
            ['certificate_number' => 'FD-005', 'product' => 'Fixed Deposit - 3 Months', 'amount' => 50000, 'interest' => 1500, 'start_date' => '2024-09-01', 'maturity_date' => '2024-12-01', 'status' => 'Active', 'current_value' => 50750, 'member_number' => 'M004'],
        ],
        'M005' => [
            ['certificate_number' => 'FD-006', 'product' => 'Fixed Deposit - 2 Years', 'amount' => 500000, 'interest' => 125000, 'start_date' => '2024-02-20', 'maturity_date' => '2026-02-20', 'status' => 'Active', 'current_value' => 555000, 'member_number' => 'M005'],
            ['certificate_number' => 'FD-007', 'product' => 'Fixed Deposit - 1 Year', 'amount' => 200000, 'interest' => 22000, 'start_date' => '2024-06-01', 'maturity_date' => '2025-06-01', 'status' => 'Active', 'current_value' => 210000, 'member_number' => 'M005'],
        ],
        'M007' => [
            ['certificate_number' => 'FD-008', 'product' => 'Fixed Deposit - 3 Years', 'amount' => 1000000, 'interest' => 375000, 'start_date' => '2024-04-01', 'maturity_date' => '2027-04-01', 'status' => 'Active', 'current_value' => 1180000, 'member_number' => 'M007'],
        ],
        'M008' => [
            ['certificate_number' => 'FD-009', 'product' => 'Fixed Deposit - 1 Year', 'amount' => 250000, 'interest' => 27500, 'start_date' => '2024-08-15', 'maturity_date' => '2025-08-15', 'status' => 'Active', 'current_value' => 258000, 'member_number' => 'M008'],
        ],
        'M009' => [
            ['certificate_number' => 'FD-010', 'product' => 'Fixed Deposit - 6 Months', 'amount' => 80000, 'interest' => 4800, 'start_date' => '2024-09-10', 'maturity_date' => '2025-03-10', 'status' => 'Active', 'current_value' => 81500, 'member_number' => 'M009'],
        ],
    ];

    protected const SWF = [
        'M001' => ['total_contribution' => 60000, 'benefits' => 5000, 'current_balance' => 55000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 5000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 5000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-08-01', 'amount' => 5000, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M001'],
        'M002' => ['total_contribution' => 84000, 'benefits' => 10000, 'current_balance' => 74000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 7000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 7000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-08-01', 'amount' => 7000, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M002'],
        'M003' => ['total_contribution' => 36000, 'benefits' => 0, 'current_balance' => 36000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 3000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 3000, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M003'],
        'M004' => ['total_contribution' => 24000, 'benefits' => 0, 'current_balance' => 24000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 2000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 2000, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M004'],
        'M005' => ['total_contribution' => 96000, 'benefits' => 20000, 'current_balance' => 76000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 8000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 8000, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M005'],
        'M006' => ['total_contribution' => 12000, 'benefits' => 0, 'current_balance' => 12000, 'contribution_history' => [
            ['date' => '2024-05-01', 'amount' => 2000, 'description' => 'Last SWF Contribution'],
        ], 'member_number' => 'M006'],
        'M007' => ['total_contribution' => 120000, 'benefits' => 15000, 'current_balance' => 105000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 10000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 10000, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M007'],
        'M008' => ['total_contribution' => 63000, 'benefits' => 0, 'current_balance' => 63000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 7000, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 7000, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M008'],
        'M009' => ['total_contribution' => 35000, 'benefits' => 0, 'current_balance' => 35000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 3500, 'description' => 'Monthly SWF Contribution'],
            ['date' => '2024-09-01', 'amount' => 3500, 'description' => 'Monthly SWF Contribution'],
        ], 'member_number' => 'M009'],
        'M010' => ['total_contribution' => 3000, 'benefits' => 0, 'current_balance' => 3000, 'contribution_history' => [
            ['date' => '2024-10-01', 'amount' => 3000, 'description' => 'Registration SWF'],
        ], 'member_number' => 'M010'],
    ];

    protected const INVESTMENTS = [
        'M001' => [
            ['product' => 'Money Market Fund', 'amount_invested' => 100000, 'units' => 10000, 'current_value' => 108500, 'profit_earned' => 8500, 'return_rate' => 8.5, 'member_number' => 'M001', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 108500],
                ['date' => '2024-09-01', 'type' => 'Valuation', 'value' => 107200],
                ['date' => '2024-07-01', 'type' => 'Investment', 'value' => 100000],
            ]],
            ['product' => 'Equity Fund', 'amount_invested' => 150000, 'units' => 7500, 'current_value' => 172500, 'profit_earned' => 22500, 'return_rate' => 15.0, 'member_number' => 'M001', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 172500],
                ['date' => '2024-01-01', 'type' => 'Investment', 'value' => 150000],
            ]],
        ],
        'M002' => [
            ['product' => 'Money Market Fund', 'amount_invested' => 200000, 'units' => 20000, 'current_value' => 217000, 'profit_earned' => 17000, 'return_rate' => 8.5, 'member_number' => 'M002', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 217000],
                ['date' => '2024-04-01', 'type' => 'Investment', 'value' => 200000],
            ]],
        ],
        'M003' => [
            ['product' => 'Balanced Fund', 'amount_invested' => 80000, 'units' => 8000, 'current_value' => 86400, 'profit_earned' => 6400, 'return_rate' => 8.0, 'member_number' => 'M003', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 86400],
                ['date' => '2024-05-01', 'type' => 'Investment', 'value' => 80000],
            ]],
        ],
        'M005' => [
            ['product' => 'Agricultural Commodity Fund', 'amount_invested' => 300000, 'units' => 30000, 'current_value' => 336000, 'profit_earned' => 36000, 'return_rate' => 12.0, 'member_number' => 'M005', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 336000],
                ['date' => '2024-03-01', 'type' => 'Investment', 'value' => 300000],
            ]],
            ['product' => 'Money Market Fund', 'amount_invested' => 100000, 'units' => 10000, 'current_value' => 104000, 'profit_earned' => 4000, 'return_rate' => 8.5, 'member_number' => 'M005', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 104000],
                ['date' => '2024-07-01', 'type' => 'Investment', 'value' => 100000],
            ]],
        ],
        'M007' => [
            ['product' => 'Equity Fund', 'amount_invested' => 500000, 'units' => 25000, 'current_value' => 595000, 'profit_earned' => 95000, 'return_rate' => 19.0, 'member_number' => 'M007', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 595000],
                ['date' => '2024-02-01', 'type' => 'Investment', 'value' => 500000],
            ]],
            ['product' => 'Bond Fund', 'amount_invested' => 300000, 'units' => 30000, 'current_value' => 330000, 'profit_earned' => 30000, 'return_rate' => 10.0, 'member_number' => 'M007', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 330000],
                ['date' => '2024-01-01', 'type' => 'Investment', 'value' => 300000],
            ]],
        ],
        'M008' => [
            ['product' => 'Money Market Fund', 'amount_invested' => 150000, 'units' => 15000, 'current_value' => 159000, 'profit_earned' => 9000, 'return_rate' => 8.5, 'member_number' => 'M008', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 159000],
                ['date' => '2024-06-01', 'type' => 'Investment', 'value' => 150000],
            ]],
        ],
        'M009' => [
            ['product' => 'Balanced Fund', 'amount_invested' => 100000, 'units' => 10000, 'current_value' => 107000, 'profit_earned' => 7000, 'return_rate' => 7.0, 'member_number' => 'M009', 'history' => [
                ['date' => '2024-10-01', 'type' => 'Valuation', 'value' => 107000],
                ['date' => '2024-04-01', 'type' => 'Investment', 'value' => 100000],
            ]],
        ],
    ];

    protected const SHARES = [
        'M001' => [
            ['share_number' => 'SH-001', 'type' => 'Ordinary', 'quantity' => 100, 'value_per_share' => 1000, 'purchase_date' => '2023-01-15', 'status' => 'active'],
            ['share_number' => 'SH-002', 'type' => 'Preference', 'quantity' => 50, 'value_per_share' => 1500, 'purchase_date' => '2023-06-20', 'status' => 'active'],
        ],
        'M002' => [
            ['share_number' => 'SH-003', 'type' => 'Ordinary', 'quantity' => 150, 'value_per_share' => 1000, 'purchase_date' => '2023-02-20', 'status' => 'active'],
        ],
        'M003' => [
            ['share_number' => 'SH-004', 'type' => 'Ordinary', 'quantity' => 200, 'value_per_share' => 1000, 'purchase_date' => '2023-03-10', 'status' => 'active'],
        ],
        'M005' => [
            ['share_number' => 'SH-005', 'type' => 'Ordinary', 'quantity' => 300, 'value_per_share' => 1000, 'purchase_date' => '2023-05-18', 'status' => 'active'],
        ],
        'M007' => [
            ['share_number' => 'SH-006', 'type' => 'Preference', 'quantity' => 100, 'value_per_share' => 1500, 'purchase_date' => '2023-07-30', 'status' => 'active'],
        ],
    ];

    protected const STATEMENT_TYPES = ['savings', 'loans', 'deposits', 'swf', 'investments'];

    public function getSheetData(string $sheetName, ?string $range = null): array
    {
        return match (strtolower(trim($sheetName))) {
            'members', 'member' => $this->members,
            'loans', 'loan' => self::LOANS,
            'savings', 'saving' => $this->flattenNested(self::SAVINGS),
            'deposits', 'deposit' => $this->flattenNested(self::DEPOSITS),
            'swf', 'social_welfare' => $this->flattenNested(self::SWF),
            'investments', 'investment' => $this->flattenNested(self::INVESTMENTS),
            'shares', 'share' => $this->flattenNested(self::SHARES),
            default => [],
        };
    }

    public function getMemberByNumber(string $memberNumber): ?array
    {
        $memberNumber = strtoupper(trim($memberNumber));
        foreach ($this->members as $member) {
            if ($member['member_number'] === $memberNumber) {
                return $member;
            }
        }

        return null;
    }

    public function getAllMembers(): array
    {
        return $this->members;
    }

    public function getMemberLoans(string $memberNumber): array
    {
        $memberNumber = strtoupper(trim($memberNumber));

        return array_values(array_filter(self::LOANS, static fn(array $loan): bool => $loan['member_number'] === $memberNumber));
    }

    public function getMemberSavings(string $memberNumber): array
    {
        $memberNumber = strtoupper(trim($memberNumber));

        return self::SAVINGS[$memberNumber] ?? [
            'balance' => 0,
            'interest_earned' => 0,
            'running_balance' => 0,
            'transactions' => [],
            'member_number' => $memberNumber,
        ];
    }

    public function getMemberDeposits(string $memberNumber): array
    {
        $memberNumber = strtoupper(trim($memberNumber));

        return self::DEPOSITS[$memberNumber] ?? [];
    }

    public function getMemberSwf(string $memberNumber): array
    {
        $memberNumber = strtoupper(trim($memberNumber));

        return self::SWF[$memberNumber] ?? [
            'total_contribution' => 0,
            'benefits' => 0,
            'current_balance' => 0,
            'contribution_history' => [],
            'member_number' => $memberNumber,
        ];
    }

    public function getMemberInvestments(string $memberNumber): array
    {
        $memberNumber = strtoupper(trim($memberNumber));

        return self::INVESTMENTS[$memberNumber] ?? [];
    }

    public function getMemberShares(string $memberNumber): array
    {
        $memberNumber = strtoupper(trim($memberNumber));

        return self::SHARES[$memberNumber] ?? [];
    }

    public function getMemberStatements(string $memberNumber, string $type): array
    {
        $memberNumber = strtoupper(trim($memberNumber));
        $type = strtolower(trim($type));

        if (! in_array($type, self::STATEMENT_TYPES, true)) {
            return [];
        }

        return match ($type) {
            'savings' => $this->getMemberSavings($memberNumber)['transactions'] ?? [],
            'loans' => $this->buildLoanStatement($this->getMemberLoans($memberNumber)),
            'deposits' => $this->getMemberDeposits($memberNumber),
            'swf' => $this->getMemberSwf($memberNumber)['contribution_history'] ?? [],
            'investments' => $this->flattenInvestmentHistory($this->getMemberInvestments($memberNumber)),
            default => [],
        };
    }

    public function getDashboardTotals(): array
    {
        $totalSavings = 0;
        $totalSwf = 0;
        foreach (self::SAVINGS as $sav) {
            $totalSavings += $sav['balance'] ?? 0;
        }
        foreach (self::SWF as $swf) {
            $totalSwf += $swf['current_balance'] ?? 0;
        }

        $totalLoans = 0;
        foreach (self::LOANS as $loan) {
            $totalLoans += $loan['outstanding_balance'] ?? 0;
        }

        $totalDeposits = 0;
        foreach (self::DEPOSITS as $depositList) {
            foreach ($depositList as $dep) {
                $totalDeposits += $dep['current_value'] ?? 0;
            }
        }

        $totalInvestments = 0;
        foreach (self::INVESTMENTS as $invList) {
            foreach ($invList as $inv) {
                $totalInvestments += $inv['current_value'] ?? 0;
            }
        }

        return [
            'total_members' => count($this->members),
            'total_savings' => $totalSavings,
            'total_loans' => $totalLoans,
            'total_deposits' => $totalDeposits,
            'total_investments' => $totalInvestments,
            'total_swf' => $totalSwf,
        ];
    }

    public function searchMembers(string $query): array
    {
        $query = strtolower(trim($query));
        if ($query === '') {
            return $this->members;
        }

        return array_values(array_filter($this->members, static function (array $member) use ($query): bool {
            $haystack = strtolower(implode(' ', [
                $member['name'] ?? '',
                $member['member_number'] ?? '',
                $member['phone'] ?? '',
                $member['email'] ?? '',
                $member['branch'] ?? '',
                $member['occupation'] ?? '',
                $member['employer'] ?? '',
            ]));

            return str_contains($haystack, $query);
        }));
    }

    public function addMember(array $memberData): bool
    {
        $this->members[] = $memberData;
        return true;
    }

    public function getLastSyncInfo(): array
    {
        return [
            'last_synced_at' => '2024-10-15 08:30:00',
            'source' => 'Mock / Sample Data',
            'status' => 'success',
            'records_synced' => [
                'members' => count($this->members),
                'loans' => count(self::LOANS),
                'savings_accounts' => count(self::SAVINGS),
                'deposits' => array_sum(array_map('count', self::DEPOSITS)),
                'swf_accounts' => count(self::SWF),
                'investments' => array_sum(array_map('count', self::INVESTMENTS)),
            ],
            'next_sync_at' => null,
            'duration_seconds' => 0.05,
        ];
    }

    protected function flattenNested(array $data): array
    {
        $result = [];
        foreach ($data as $items) {
            if (isset($items[0]) && is_array($items[0])) {
                foreach ($items as $item) {
                    $result[] = $item;
                }
            } else {
                $result[] = $items;
            }
        }

        return $result;
    }

    protected function buildLoanStatement(array $loans): array
    {
        $statement = [];
        foreach ($loans as $loan) {
            if (! empty($loan['disbursement_date'])) {
                $statement[] = [
                    'date' => $loan['disbursement_date'],
                    'type' => 'Disbursement',
                    'reference' => $loan['loan_number'],
                    'debit' => 0,
                    'credit' => $loan['loan_amount'],
                    'balance' => $loan['loan_amount'],
                    'description' => "Loan disbursed - {$loan['loan_product']}",
                ];
            }
            if (! empty($loan['paid_amount']) && $loan['paid_amount'] > 0) {
                $lastDate = $loan['maturity_date'] ?? date('Y-m-d');
                $statement[] = [
                    'date' => $lastDate,
                    'type' => 'Repayment',
                    'reference' => $loan['loan_number'],
                    'debit' => $loan['paid_amount'],
                    'credit' => 0,
                    'balance' => $loan['outstanding_balance'],
                    'description' => "Loan repayment - {$loan['status']}",
                ];
            }
        }

        return $statement;
    }

    protected function flattenInvestmentHistory(array $investments): array
    {
        $result = [];
        foreach ($investments as $investment) {
            if (! empty($investment['history']) && is_array($investment['history'])) {
                foreach ($investment['history'] as $event) {
                    $result[] = array_merge($event, [
                        'product' => $investment['product'] ?? null,
                    ]);
                }
            }
        }

        return $result;
    }
}
