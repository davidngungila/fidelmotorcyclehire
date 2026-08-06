<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('purpose', ['business', 'education', 'agriculture', 'personal', 'emergency', 'other', 'motorcycle'])
                  ->default('personal')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('purpose', ['business', 'education', 'agriculture', 'personal', 'emergency', 'other'])
                  ->default('personal')
                  ->change();
        });
    }
};
