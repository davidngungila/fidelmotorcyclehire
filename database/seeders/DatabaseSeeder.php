<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $memberRole = Role::firstOrCreate(['name' => 'member']);

        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@motorcyclehire.co.tz'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Motorcycle@2024'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Assign admin role
        try {
            $admin->roles()->sync([$adminRole->id]);
        } catch (\Throwable $e) {
            // Fallback if roles relationship fails
            $admin->role = 'admin';
            $admin->save();
        }

        // Create default member user
        $member = User::firstOrCreate(
            ['email' => 'customer@motorcyclehire.co.tz'],
            [
                'name' => 'John Customer',
                'password' => Hash::make('Customer@2024'),
                'role' => 'member',
                'member_number' => 'CUST001',
                'status' => 'active',
            ]
        );

        // Assign member role
        try {
            $member->roles()->sync([$memberRole->id]);
        } catch (\Throwable $e) {
            // Fallback if roles relationship fails
            $member->role = 'member';
            $member->save();
        }

        // Seed loan products
        $this->call([
            MemberTypeSeeder::class,
            LoanProductSeeder::class,
            SavingsProductSeeder::class,
            InvestmentProductSeeder::class,
        ]);

        $this->command->info('✓ Default admin user created: admin@motorcyclehire.co.tz / Motorcycle@2024');
        $this->command->info('✓ Default customer user created: customer@motorcyclehire.co.tz / Customer@2024');
        $this->command->info('✓ Member types seeded successfully');
        $this->command->info('✓ Loan products seeded successfully');
        $this->command->info('✓ Savings products seeded successfully');
        $this->command->info('✓ Investment products seeded successfully');
    }
}
