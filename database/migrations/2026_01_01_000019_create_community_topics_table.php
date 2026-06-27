<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('prompt')->nullable();
            $table->string('type')->default('discussion');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_intro')->default(false);
            $table->unsignedInteger('sort_order')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $now = now();

        DB::table('community_topics')->insert([
            [
                'title' => 'Introduce Yourself',
                'slug' => 'introduce-yourself',
                'description' => 'Start here. Share who you are, what brought you to Southern Spark, and who you hope to meet.',
                'prompt' => 'Tell the community your role, what you are building or learning, and one connection you would love to make this week.',
                'type' => 'introductions',
                'is_active' => true,
                'is_intro' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'What AI use case should Mississippi prioritize?',
                'slug' => Str::slug('What AI use case should Mississippi prioritize?'),
                'description' => 'Share one practical opportunity where AI could create meaningful impact across the state.',
                'prompt' => 'What problem should Mississippi tackle first with AI, and what would success look like?',
                'type' => 'discussion',
                'is_active' => true,
                'is_intro' => false,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'What are you teaching, building, or piloting right now?',
                'slug' => Str::slug('What are you teaching, building, or piloting right now?'),
                'description' => 'A place for educators, founders, and practitioners to swap current experiments and ideas.',
                'prompt' => 'Tell us what you are trying right now with AI and what you have learned so far.',
                'type' => 'discussion',
                'is_active' => true,
                'is_intro' => false,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Who are you hoping to meet at Southern Spark?',
                'slug' => Str::slug('Who are you hoping to meet at Southern Spark?'),
                'description' => 'Use this prompt to surface collaborations, introductions, and hallway conversations.',
                'prompt' => 'Share the kinds of people or organizations you would love to connect with during the conference.',
                'type' => 'discussion',
                'is_active' => true,
                'is_intro' => false,
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('community_topics');
    }
};
