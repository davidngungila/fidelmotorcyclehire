<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvestmentProduct;

class InvestmentProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $investmentProducts = [
            [
                'name' => '8.6% Four-years FIA (Priced at TZS 110/100)',
                'code' => 'FIA-110',
                'type' => 'fixed',
                'interest_rate' => 8.6,
                'min_investment' => 110000,
                'max_investment' => null,
                'duration_months' => 48,
                'auto_renew' => false,
                'description' => 'Fixed Investment Account with 8.6% interest rate over 4 years. Priced at TZS 110 per 100 units.',
                'status' => 'active',
            ],
            [
                'name' => '10% Four-years FIA (Priced at TZS 120/100)',
                'code' => 'FIA-120',
                'type' => 'fixed',
                'interest_rate' => 10.0,
                'min_investment' => 120000,
                'max_investment' => null,
                'duration_months' => 48,
                'auto_renew' => false,
                'description' => 'Fixed Investment Account with 10% interest rate over 4 years. Priced at TZS 120 per 100 units.',
                'status' => 'active',
            ],
        ];

        foreach ($investmentProducts as $product) {
            InvestmentProduct::updateOrCreate(
                ['code' => $product['code']],
                $product
            );
        }
    }
}
