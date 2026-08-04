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
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('motorcycle_id')->nullable()->constrained('motorcycles')->nullOnDelete();
            $table->decimal('motorcycle_price', 15, 2)->nullable()->comment('Bei ya pikipiki');
            $table->decimal('down_payment', 15, 2)->nullable()->comment('Down Payment');
            $table->decimal('financed_amount', 15, 2)->nullable()->comment('Kiasi kilichofadhiliwa');
            $table->string('payment_schedule')->nullable()->comment('Ratiba ya malipo');
            $table->decimal('late_payment_penalty', 15, 2)->default(0)->comment('Penalties za kuchelewa');
            $table->decimal('total_penalties', 15, 2)->default(0)->comment('Total accumulated penalties');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['motorcycle_id']);
            $table->dropColumn([
                'motorcycle_id',
                'motorcycle_price',
                'down_payment',
                'financed_amount',
                'payment_schedule',
                'late_payment_penalty',
                'total_penalties'
            ]);
        });
    }
};
