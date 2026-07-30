<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MemberType;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memberTypes = [
            [
                'name' => 'Founder',
                'code' => 'FOUNDER',
                'description' => 'Founding members who established the organization with full voting rights and office-holding privileges',
                'registration_fee' => 0,
                'monthly_contribution' => 50000,
                'min_savings' => 100000,
                'max_loan_multiplier' => 5,
                'interest_rate_discount' => 1.5,
                'can_vote' => true,
                'can_hold_office' => true,
                'priority' => 100,
                'status' => 'active',
            ],
            [
                'name' => 'Ordinary',
                'code' => 'ORDINARY',
                'description' => 'Regular members with standard benefits including voting rights',
                'registration_fee' => 10000,
                'monthly_contribution' => 20000,
                'min_savings' => 50000,
                'max_loan_multiplier' => 3,
                'interest_rate_discount' => 0.5,
                'can_vote' => true,
                'can_hold_office' => true,
                'priority' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Promoter',
                'code' => 'PROMOTER',
                'description' => 'Members who actively promote the organization and recruit new members',
                'registration_fee' => 5000,
                'monthly_contribution' => 15000,
                'min_savings' => 30000,
                'max_loan_multiplier' => 2,
                'interest_rate_discount' => 0.3,
                'can_vote' => true,
                'can_hold_office' => false,
                'priority' => 40,
                'status' => 'active',
            ],
            [
                'name' => 'Associate',
                'code' => 'ASSOCIATE',
                'description' => 'Associate members with limited benefits, no voting rights',
                'registration_fee' => 5000,
                'monthly_contribution' => 10000,
                'min_savings' => 20000,
                'max_loan_multiplier' => 1,
                'interest_rate_discount' => 0,
                'can_vote' => false,
                'can_hold_office' => false,
                'priority' => 30,
                'status' => 'active',
            ],
            [
                'name' => 'Scholar',
                'code' => 'SCHOLAR',
                'description' => 'Student members with special benefits for education purposes',
                'registration_fee' => 0,
                'monthly_contribution' => 5000,
                'min_savings' => 10000,
                'max_loan_multiplier' => 1,
                'interest_rate_discount' => 1.0,
                'can_vote' => false,
                'can_hold_office' => false,
                'priority' => 20,
                'status' => 'active',
            ],
        ];

        foreach ($memberTypes as $type) {
            MemberType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
