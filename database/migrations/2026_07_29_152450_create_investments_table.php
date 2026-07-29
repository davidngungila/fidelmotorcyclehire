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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('investment_product_id')->nullable()->constrained('investment_products')->nullOnDelete();
            $table->string('member_number')->nullable();
            $table->string('investment_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->date('investment_date');
            $table->date('maturity_date')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('expected_return', 15, 2)->nullable();
            $table->decimal('actual_return', 15, 2)->nullable();
            $table->enum('status', ['active', 'matured', 'withdrawn', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
