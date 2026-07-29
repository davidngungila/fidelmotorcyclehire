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
        Schema::create('investment_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'flexible', 'mutual_fund', 'bonds', 'stocks'])->default('fixed');
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('min_investment', 15, 2)->default(0);
            $table->decimal('max_investment', 15, 2)->nullable();
            $table->integer('duration_months')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_products');
    }
};
