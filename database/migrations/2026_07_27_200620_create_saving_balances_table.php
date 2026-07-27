<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('saving_balances', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->decimal('monthly_saving_target', 15, 2)->default(0);
            $table->decimal('monthly_total_savings_deposits', 15, 2)->default(0);
            $table->decimal('monthly_goal_achievement', 10, 2)->default(0);
            $table->decimal('overall_saving_goal', 15, 2)->default(0);
            $table->decimal('total_saved', 15, 2)->default(0);
            $table->decimal('overall_goal_achievement', 10, 2)->default(0);
            $table->decimal('flexi_opening_balance', 15, 2)->default(0);
            $table->decimal('flexi_deposit', 15, 2)->default(0);
            $table->decimal('flexi_withdrawal', 15, 2)->default(0);
            $table->decimal('flexi_balance', 15, 2)->default(0);
            $table->decimal('rda_opening_balance', 15, 2)->default(0);
            $table->decimal('rda_deposit', 15, 2)->default(0);
            $table->decimal('rda_withdrawal', 15, 2)->default(0);
            $table->decimal('rda_balance', 15, 2)->default(0);
            $table->decimal('emergency_opening_balance', 15, 2)->default(0);
            $table->decimal('emergency_deposit', 15, 2)->default(0);
            $table->decimal('emergency_withdrawal', 15, 2)->default(0);
            $table->decimal('emergency_balance', 15, 2)->default(0);
            $table->decimal('business_opening_balance', 15, 2)->default(0);
            $table->decimal('business_deposit', 15, 2)->default(0);
            $table->decimal('business_withdrawal', 15, 2)->default(0);
            $table->decimal('business_balance', 15, 2)->default(0);
            $table->decimal('total_balance', 15, 2)->default(0);
            $table->decimal('interest_payable', 15, 2)->default(0);
            $table->decimal('savings_held_for_loan_security', 15, 2)->default(0);
            $table->decimal('free_savings_emergency', 15, 2)->default(0);
            $table->decimal('free_savings_rda_flexi_business', 15, 2)->default(0);
            $table->decimal('total_free_saving', 15, 2)->default(0);
            $table->decimal('premature_withdraw_charge', 15, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')->references('customer_id')->on('customer_profiles')->onDelete('cascade');
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_balances');
    }
};
