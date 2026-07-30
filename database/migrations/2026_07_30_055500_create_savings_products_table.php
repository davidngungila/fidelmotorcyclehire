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
        Schema::create('savings_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('min_balance', 15, 2)->default(0);
            $table->decimal('min_deposit', 15, 2)->default(0);
            $table->decimal('max_deposit', 15, 2)->nullable();
            $table->integer('min_withdrawal_period_days')->default(0);
            $table->decimal('premature_withdrawal_fee', 5, 2)->default(0);
            $table->boolean('auto_interest_credit')->default(true);
            $table->enum('interest_frequency', ['monthly', 'quarterly', 'annually'])->default('monthly');
            $table->boolean('requires_notice')->default(false);
            $table->integer('notice_period_days')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_products');
    }
};
