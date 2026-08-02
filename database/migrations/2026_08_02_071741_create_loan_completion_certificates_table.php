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
        Schema::create('loan_completion_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->date('completion_date');
            $table->decimal('original_amount', 15, 2);
            $table->decimal('total_paid', 15, 2);
            $table->decimal('total_interest_paid', 15, 2)->default(0);
            $table->date('issue_date')->default(now());
            $table->string('issued_by')->nullable();
            $table->string('signature')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_completion_certificates');
    }
};
