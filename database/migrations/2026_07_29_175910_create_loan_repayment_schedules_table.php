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
        Schema::create('loan_repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->integer('installment_number');
            $table->date('due_date');
            $table->decimal('principal_amount', 15, 2)->default(0);
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->date('paid_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_repayment_schedules');
    }
};
