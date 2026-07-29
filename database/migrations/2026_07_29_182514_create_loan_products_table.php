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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('min_amount', 15, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('min_term_months')->default(1);
            $table->integer('max_term_months')->default(12);
            $table->decimal('processing_fee', 15, 2)->default(0);
            $table->decimal('late_fee', 15, 2)->default(0);
            $table->enum('interest_type', ['flat', 'reducing', 'compound'])->default('reducing');
            $table->enum('repayment_frequency', ['monthly', 'weekly', 'bi_weekly'])->default('monthly');
            $table->boolean('requires_collateral')->default(false);
            $table->boolean('requires_guarantor')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
