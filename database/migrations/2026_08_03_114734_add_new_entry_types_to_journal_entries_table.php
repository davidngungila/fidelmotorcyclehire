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
        DB::statement("ALTER TABLE journal_entries MODIFY COLUMN entry_type ENUM('manual', 'automatic', 'adjusting', 'closing', 'loan_disbursement', 'loan_repayment', 'investment', 'share_purchase', 'swf_contribution', 'deposit')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            //
        });
    }
};
