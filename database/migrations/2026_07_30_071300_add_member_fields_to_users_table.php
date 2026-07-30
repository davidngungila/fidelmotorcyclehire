<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Basic Information (only missing fields)
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('national_id')->nullable()->after('date_of_birth');
            $table->string('passport_license')->nullable()->after('national_id');
            
            // Contact Information (only missing fields)
            $table->string('alternative_phone')->nullable()->after('phone');
            $table->string('region')->nullable()->after('alternative_phone');
            $table->string('district')->nullable()->after('region');
            $table->string('ward')->nullable()->after('district');
            $table->string('street_village')->nullable()->after('ward');
            $table->text('physical_address')->nullable()->after('street_village');
            
            // Membership Details (only missing fields)
            $table->string('membership_category')->nullable()->after('branch');
            $table->decimal('monthly_income', 15, 2)->nullable()->after('occupation');
            $table->string('introduced_by')->nullable()->after('monthly_income');
            $table->decimal('joining_fee', 15, 2)->nullable()->after('introduced_by');
            $table->decimal('shares_purchased', 15, 2)->nullable()->after('joining_fee');
            $table->decimal('initial_savings', 15, 2)->nullable()->after('shares_purchased');
            
            // Account Information (only missing fields)
            $table->string('username')->nullable()->after('initial_savings');
            $table->boolean('email_verified')->default(false)->after('email_verified_at');
            $table->boolean('phone_verified')->default(false)->after('email_verified');
            
            // Additional Information
            $table->text('notes')->nullable()->after('phone_verified');
            $table->json('tags')->nullable()->after('notes');
            $table->json('custom_fields')->nullable()->after('tags');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'national_id',
                'passport_license',
                'alternative_phone',
                'region',
                'district',
                'ward',
                'street_village',
                'physical_address',
                'membership_category',
                'monthly_income',
                'introduced_by',
                'joining_fee',
                'shares_purchased',
                'initial_savings',
                'username',
                'email_verified',
                'phone_verified',
                'notes',
                'tags',
                'custom_fields',
            ]);
        });
    }
};
