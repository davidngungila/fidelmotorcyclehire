<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SavingsProduct;

class SavingsProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $savingsProducts = [
            [
                'name' => 'Business Saving',
                'code' => 'BS',
                'description' => 'Savings account for business purposes with competitive interest rate',
                'interest_rate' => 2.96, // 0.0296 = 2.96%
                'min_balance' => 0,
                'min_deposit' => 10000,
                'max_deposit' => null,
                'min_withdrawal_period_days' => 0,
                'premature_withdrawal_fee' => 0,
                'auto_interest_credit' => true,
                'interest_frequency' => 'monthly',
                'requires_notice' => false,
                'notice_period_days' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'FeedTan Flexi',
                'code' => 'FF',
                'description' => 'Flexible savings account with high interest rate',
                'interest_rate' => 5.37, // 0.0537 = 5.37%
                'min_balance' => 0,
                'min_deposit' => 5000,
                'max_deposit' => null,
                'min_withdrawal_period_days' => 0,
                'premature_withdrawal_fee' => 0,
                'auto_interest_credit' => true,
                'interest_frequency' => 'monthly',
                'requires_notice' => false,
                'notice_period_days' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Emergence Fund',
                'code' => 'EF',
                'description' => 'Emergency fund savings account with no interest',
                'interest_rate' => 0, // 0% interest
                'min_balance' => 0,
                'min_deposit' => 1000,
                'max_deposit' => null,
                'min_withdrawal_period_days' => 0,
                'premature_withdrawal_fee' => 0,
                'auto_interest_credit' => true,
                'interest_frequency' => 'monthly',
                'requires_notice' => false,
                'notice_period_days' => 0,
                'status' => 'active',
            ],
            [
                'name' => 'Recurrent Deposit Account (RDA)',
                'code' => 'RDA',
                'description' => 'Recurring deposit with tiered interest rates: 0% for <100k, 5.84% for >=100k, 6.3% for >=200k, 6.78% for >299k',
                'interest_rate' => 5.84, // Base rate for >=100k
                'min_balance' => 100000,
                'min_deposit' => 100000,
                'max_deposit' => null,
                'min_withdrawal_period_days' => 30,
                'premature_withdrawal_fee' => 0,
                'auto_interest_credit' => true,
                'interest_frequency' => 'monthly',
                'requires_notice' => true,
                'notice_period_days' => 7,
                'status' => 'active',
            ],
        ];

        foreach ($savingsProducts as $product) {
            SavingsProduct::updateOrCreate(
                ['code' => $product['code']],
                $product
            );
        }
    }
}
