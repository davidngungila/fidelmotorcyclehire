<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE accounts MODIFY COLUMN account_subtype ENUM('current_asset', 'fixed_asset', 'loan_receivable', 'investment', 'current_liability', 'long_term_liability', 'savings_deposit', 'swf_fund', 'owners_equity', 'share_capital', 'operating_revenue', 'non_operating_revenue', 'interest_income', 'operating_expense', 'non_operating_expense') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            //
        });
    }
};
