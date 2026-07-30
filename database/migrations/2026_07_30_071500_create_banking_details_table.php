<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('mobile_money_network')->nullable();
            $table->string('mobile_wallet_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banking_details');
    }
};
