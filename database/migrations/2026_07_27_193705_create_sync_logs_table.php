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
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sync_type');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->string('status')->default('running');
            $table->integer('records_synced')->default(0);
            $table->integer('records_failed')->default(0);
            $table->json('summary')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
            
            $table->index('sync_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
