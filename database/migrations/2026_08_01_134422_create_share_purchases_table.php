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
        Schema::create('share_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('share_product_id')->constrained()->onDelete('cascade');
            $table->integer('number_of_shares')->default(0);
            $table->decimal('price_per_share', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->date('purchase_date');
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
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
        Schema::dropIfExists('share_purchases');
    }
};
