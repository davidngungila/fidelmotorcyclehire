<?php

namespace Database\Seeders;

use App\Models\WhatsAppTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsAppTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'medicine_reminder',
                'description' => 'Reminder for taking medicine',
                'parameters' => ['name', 'medicine', 'time'],
                'category' => 'utility',
                'language' => 'en',
                'is_active' => true,
            ],
            [
                'name' => 'appointment_reminder',
                'description' => 'Reminder for upcoming appointments',
                'parameters' => ['name', 'date', 'time', 'location'],
                'category' => 'utility',
                'language' => 'en',
                'is_active' => true,
            ],
            [
                'name' => 'payment_confirmation',
                'description' => 'Confirmation of payment received',
                'parameters' => ['name', 'amount', 'reference', 'date'],
                'category' => 'utility',
                'language' => 'en',
                'is_active' => true,
            ],
            [
                'name' => 'welcome_message',
                'description' => 'Welcome message for new members',
                'parameters' => ['name'],
                'category' => 'marketing',
                'language' => 'en',
                'is_active' => true,
            ],
            [
                'name' => 'order_confirmation',
                'description' => 'Confirmation of order placement',
                'parameters' => ['name', 'order_number', 'total', 'delivery_date'],
                'category' => 'utility',
                'language' => 'en',
                'is_active' => true,
            ],
            [
                'name' => 'loan_approval',
                'description' => 'Notification of loan approval',
                'parameters' => ['name', 'amount', 'repayment_date'],
                'category' => 'utility',
                'language' => 'en',
                'is_active' => true,
            ],
            [
                'name' => 'loan_repayment_reminder',
                'description' => 'Reminder for loan repayment',
                'parameters' => ['name', 'amount', 'due_date'],
                'category' => 'utility',
                'language' => 'en',
                'is_active' => true,
            ],
            [
                'name' => 'savings_deposit_confirmation',
                'description' => 'Confirmation of savings deposit',
                'parameters' => ['name', 'amount', 'balance'],
                'category' => 'utility',
                'language' => 'en',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            WhatsAppTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
