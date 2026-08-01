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
            $table->string('employment_status')->nullable()->after('purpose_description');
            $table->string('employer_name')->nullable()->after('employment_status');
            $table->decimal('monthly_income', 15, 2)->nullable()->after('employer_name');
            $table->decimal('other_income', 15, 2)->nullable()->after('monthly_income');
            $table->integer('work_experience')->nullable()->after('other_income');
            $table->string('repayment_frequency')->nullable()->after('notes');
            $table->integer('preferred_repayment_date')->nullable()->after('repayment_frequency');
            $table->decimal('collateral_value', 15, 2)->nullable()->after('preferred_repayment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'employment_status',
                'employer_name',
                'monthly_income',
                'other_income',
                'work_experience',
                'repayment_frequency',
                'preferred_repayment_date',
                'collateral_value'
            ]);
        });
    }
};
