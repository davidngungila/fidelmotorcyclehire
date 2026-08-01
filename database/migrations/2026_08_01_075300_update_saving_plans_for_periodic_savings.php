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
        Schema::table('saving_plans', function (Blueprint $table) {
            $table->dropColumn('monthly_goal');
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            $table->string('period_type')->nullable()->after('membership'); // 'daily', 'weekly', 'monthly'
            $table->integer('period_value')->nullable()->after('period_type'); // number of periods
            $table->date('start_date')->nullable()->after('period_value');
            $table->decimal('periodic_amount', 10, 2)->nullable()->after('target_date');
            $table->json('payment_schedule')->nullable()->after('periodic_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saving_plans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'period_type', 'period_value', 'start_date', 'periodic_amount', 'payment_schedule']);
            $table->decimal('monthly_goal', 10, 2)->nullable()->after('membership');
        });
    }
};
