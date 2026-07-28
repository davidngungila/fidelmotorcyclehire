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
        Schema::create('loans_information', function (Blueprint $table) {
            $table->id();
            $table->string('loan_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('loan_type');
            $table->decimal('loan_amount', 15, 2);
            $table->string('nature')->nullable();
            $table->decimal('interest_rate_pm', 8, 2)->nullable();
            $table->integer('duration_months')->nullable();
            $table->date('loan_start_date')->nullable();
            $table->date('loan_maturity_date')->nullable();
            $table->decimal('total_payable', 15, 2)->nullable();
            $table->decimal('monthly_installment', 15, 2)->nullable();
            $table->decimal('monthly_principal', 15, 2)->nullable();
            $table->decimal('principal_paid_to_date', 15, 2)->nullable();
            $table->decimal('termination_fee', 15, 2)->nullable();
            $table->decimal('total_paid', 15, 2)->nullable();
            $table->decimal('outstanding_balance', 15, 2)->nullable();
            $table->string('loan_status')->nullable();
            $table->string('loan_guarantor')->nullable();
            $table->integer('number_of_paid_installments')->nullable();
            $table->integer('number_of_unpaid_installments')->nullable();
            $table->string('this_month_loan_status')->nullable();
            $table->decimal('balance_after_payment', 15, 2)->nullable();
            $table->string('loan_agreement_ref_no')->nullable();
            $table->timestamps();
            
            $table->index('loan_id');
            $table->index('user_id');
            $table->index('customer_id');
            $table->index('loan_status');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans_information');
    }
};
