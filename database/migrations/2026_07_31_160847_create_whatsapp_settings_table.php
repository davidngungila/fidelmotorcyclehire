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
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('personal_access_token')->nullable()->comment('Bearer token for account-level operations');
            $table->string('session_api_key')->nullable()->comment('API key for specific WhatsApp session');
            $table->string('session_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('session_status')->nullable()->default('disconnected');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_settings');
    }
};
