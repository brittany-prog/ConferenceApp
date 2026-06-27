<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
            $table->timestamp('awarded_at');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'badge_id']);
        });

        $now = now();

        DB::table('badges')->insert([
            ['name' => 'Early Spark', 'slug' => 'early-spark', 'description' => 'Completed a rich attendee profile.', 'icon' => 'spark', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Introduced Yourself', 'slug' => 'introduced-yourself', 'description' => 'Posted your conference introduction.', 'icon' => 'wave', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Conversation Starter', 'slug' => 'conversation-starter', 'description' => 'Joined the discussion by replying to a community prompt.', 'icon' => 'chat', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Community Contributor', 'slug' => 'community-contributor', 'description' => 'Added multiple thoughtful contributions to the community.', 'icon' => 'community', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Agenda Builder', 'slug' => 'agenda-builder', 'description' => 'Saved multiple sessions to build your event plan.', 'icon' => 'calendar', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Feedback Friend', 'slug' => 'feedback-friend', 'description' => 'Shared session feedback with the organizers.', 'icon' => 'star', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sponsor Scout', 'slug' => 'sponsor-scout', 'description' => 'Explored sponsor profiles and marked interest.', 'icon' => 'map', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Southern Spark Champion', 'slug' => 'southern-spark-champion', 'description' => 'Reached a standout level of participation across the app.', 'icon' => 'trophy', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};
