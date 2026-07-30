<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('national_id')->nullable()->unique();
            $table->string('passport_driving_license')->nullable();
            $table->date('registration_date')->default(now());
            $table->enum('status', ['active', 'pending', 'suspended'])->default('pending');
            
            // Contact Information
            $table->string('phone_number')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('email_address')->nullable();
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('street_village')->nullable();
            $table->text('physical_address')->nullable();
            
            // Membership Details
            $table->foreignId('branch_id')->nullable();
            $table->string('membership_category')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer_business')->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->string('introduced_by')->nullable();
            $table->decimal('joining_fee', 15, 2)->default(0);
            $table->decimal('shares_purchased', 15, 2)->default(0);
            $table->decimal('initial_savings_deposit', 15, 2)->default(0);
            
            // Next of Kin
            $table->string('kin_full_name')->nullable();
            $table->string('kin_relationship')->nullable();
            $table->string('kin_phone_number')->nullable();
            $table->text('kin_address')->nullable();
            
            // Banking & Mobile Money
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('mobile_money_network')->nullable();
            $table->string('mobile_wallet_number')->nullable();
            
            // Documents
            $table->string('passport_photo')->nullable();
            $table->string('national_id_copy')->nullable();
            $table->string('signature')->nullable();
            $table->json('other_attachments')->nullable();
            
            // Additional Information
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->json('custom_fields')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
