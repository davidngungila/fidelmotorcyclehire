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
        Schema::create('share_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_per_share', 15, 2)->default(0);
            $table->integer('minimum_shares')->default(1);
            $table->integer('maximum_shares')->nullable();
            $table->decimal('dividend_rate', 5, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'closed'])->default('active');
            $table->date('issue_date')->nullable();
            $table->date('maturity_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_products');
    }
};
