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
        Schema::table('whatsapp_settings', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['api_key', 'account']);
            
            // Add new columns
            $table->string('personal_access_token')->nullable()->after('id')->comment('Bearer token for account-level operations');
            $table->string('session_api_key')->nullable()->after('personal_access_token')->comment('API key for specific WhatsApp session');
            $table->string('session_name')->nullable()->after('session_api_key');
            $table->string('phone_number')->nullable()->after('session_name');
            $table->string('session_status')->nullable()->default('disconnected')->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_settings', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['personal_access_token', 'session_api_key', 'session_name', 'phone_number', 'session_status']);
            
            // Add back old columns
            $table->string('api_key')->nullable()->after('id');
            $table->string('account')->nullable()->after('api_key');
        });
    }
};
