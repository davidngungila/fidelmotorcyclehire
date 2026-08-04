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
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('engine_number')->unique();
            $table->string('chassis_number')->unique();
            $table->string('registration_number')->nullable()->unique();
            $table->string('colour');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->enum('status', ['Available', 'Assigned', 'Sold', 'Under Repair'])->default('Available');
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('purchase_date')->nullable();
            $table->date('sale_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
