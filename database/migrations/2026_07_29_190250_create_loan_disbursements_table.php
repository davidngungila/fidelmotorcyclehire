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
        Schema::create('loan_disbursements', function (Blueprint $table) {
            $table->id();
            $table->string('disbursement_number')->unique();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->string('loan_number');
            $table->string('member_number');
            $table->string('member_name');
            $table->string('loan_product');
            $table->decimal('approved_amount', 15, 2)->default(0);
            $table->decimal('disbursed_amount', 15, 2)->default(0);
            $table->date('disbursement_date');
            $table->enum('disbursement_method', ['bank_transfer', 'mobile_money', 'cash', 'cheque'])->default('bank_transfer');
            $table->string('account_wallet')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('repayment_period')->default(0);
            $table->date('first_repayment_date')->nullable();
            $table->date('maturity_date')->nullable();
            $table->decimal('processing_fee', 15, 2)->default(0);
            $table->decimal('insurance_fee', 15, 2)->default(0);
            $table->decimal('other_deductions', 15, 2)->default(0);
            $table->decimal('net_amount_paid', 15, 2)->default(0);
            $table->foreignId('disbursed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'disbursed', 'cancelled', 'reversed'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_disbursements');
    }
};
