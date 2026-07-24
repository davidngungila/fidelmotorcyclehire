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
            ['email' => 'admin@membersportal.co.tz'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
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
            ['email' => 'member@membersportal.co.tz'],
            [
                'name' => 'John Member',
                'password' => Hash::make('member123'),
                'role' => 'member',
                'member_number' => 'MEM001',
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

        $this->command->info('✓ Default admin user created: admin@membersportal.co.tz / admin123');
        $this->command->info('✓ Default member user created: member@membersportal.co.tz / member123');
    }
}
