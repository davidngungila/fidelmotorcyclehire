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
        Schema::create('share_dividends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('share_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('share_certificate_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('number_of_shares')->default(0);
            $table->decimal('dividend_per_share', 15, 2)->default(0);
            $table->decimal('total_dividend', 15, 2)->default(0);
            $table->date('declaration_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['declared', 'paid', 'pending'])->default('pending');
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
        Schema::dropIfExists('share_dividends');
    }
};
