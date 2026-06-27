<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('title')->nullable()->after('email');
            $table->string('organization')->nullable()->after('title');
            $table->text('bio')->nullable()->after('organization');
            $table->string('profile_photo_path')->nullable()->after('bio');
            $table->boolean('is_speaker')->default(false)->after('can_login');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'organization',
                'bio',
                'profile_photo_path',
                'is_speaker',
            ]);
        });
    }
};
