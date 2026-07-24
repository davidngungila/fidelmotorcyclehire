<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('member')->index()->after('password');
            $table->string('member_number')->nullable()->unique()->after('role');
            $table->string('phone')->nullable()->after('member_number');
            $table->string('gender')->nullable()->after('phone');
            $table->text('address')->nullable()->after('gender');
            $table->string('occupation')->nullable()->after('address');
            $table->string('employer')->nullable()->after('occupation');
            $table->string('branch')->nullable()->after('employer');
            $table->string('photo')->nullable()->after('branch');
            $table->string('status')->default('active')->after('photo');
            $table->date('registration_date')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropUnique(['member_number']);
            $table->dropColumn([
                'role',
                'member_number',
                'phone',
                'gender',
                'address',
                'occupation',
                'employer',
                'branch',
                'photo',
                'status',
                'registration_date',
            ]);
        });
    }
};
