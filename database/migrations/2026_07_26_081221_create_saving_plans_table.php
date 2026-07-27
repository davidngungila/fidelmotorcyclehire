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
        Schema::create('saving_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('memberid');
            $table->string('membership');
            $table->decimal('monthly_goal', 15, 2);
            $table->decimal('goal', 15, 2);
            $table->timestamps();
            
            $table->index('memberid');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_plans');
    }
};
