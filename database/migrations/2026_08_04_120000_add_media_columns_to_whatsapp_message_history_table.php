<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_message_history', function (Blueprint $table) {
            $table->string('media_type')->nullable()->after('message_type')->comment('text, image, video, document, audio, sticker, contact, location');
            $table->json('media_data')->nullable()->after('media_type')->comment('Additional media data (URLs, filenames, etc.)');
            $table->index('media_type');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_message_history', function (Blueprint $table) {
            $table->dropIndex(['media_type']);
            $table->dropColumn(['media_type', 'media_data']);
        });
    }
};
