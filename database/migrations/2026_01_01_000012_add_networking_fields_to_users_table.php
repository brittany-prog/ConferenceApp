<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('location')->nullable()->after('organization');
            $table->string('interests')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('interests');
            $table->string('website_url')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'interests',
                'linkedin_url',
                'website_url',
            ]);
        });
    }
};
