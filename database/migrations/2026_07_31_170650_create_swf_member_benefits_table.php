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
        Schema::create('swf_member_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swf_member_id')->constrained('swf_members')->onDelete('cascade');
            $table->foreignId('swf_benefit_id')->constrained('swf_benefits')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('received_date');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swf_member_benefits');
    }
};
