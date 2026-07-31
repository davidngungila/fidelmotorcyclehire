<?php

namespace Database\Seeders;

use App\Models\SwfBenefit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SwfBenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $benefits = [
            [
                'name' => 'Emergency Assistance',
                'description' => 'Financial assistance for emergency situations such as medical emergencies, accidents, or unexpected crises.',
                'category' => 'emergency',
                'max_amount' => 500000,
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Education Bursary',
                'description' => 'Financial support for education expenses including school fees, books, and supplies for members and their dependents.',
                'category' => 'education',
                'max_amount' => 300000,
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Funeral Assistance',
                'description' => 'Financial support for funeral expenses of members or their immediate family members.',
                'category' => 'funeral',
                'max_amount' => 200000,
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Medical Support',
                'description' => 'Financial assistance for medical expenses including hospital bills, medication, and treatment costs.',
                'category' => 'health',
                'max_amount' => 400000,
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Maternity Support',
                'description' => 'Financial support for maternity-related expenses for female members and their spouses.',
                'category' => 'welfare',
                'max_amount' => 150000,
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Wedding Gift',
                'description' => 'Monetary gift for members getting married to help with wedding expenses.',
                'category' => 'welfare',
                'max_amount' => 100000,
                'requires_approval' => false,
                'is_active' => true,
            ],
        ];

        foreach ($benefits as $benefit) {
            SwfBenefit::updateOrCreate(
                ['name' => $benefit['name']],
                $benefit
            );
        }
    }
}
