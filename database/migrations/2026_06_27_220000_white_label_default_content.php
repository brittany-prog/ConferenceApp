<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('community_topics')
            ->where('slug', 'introduce-yourself')
            ->update([
                'description' => 'Start here. Share who you are, what brought you to the event, and who you hope to meet.',
                'prompt' => 'Tell the community your role, what you are building or learning, and one connection you would love to make this week.',
            ]);

        DB::table('community_topics')
            ->where('slug', Str::slug('What AI use case should Mississippi prioritize?'))
            ->update([
                'title' => 'What challenge should this community prioritize?',
                'slug' => Str::slug('What challenge should this community prioritize?'),
                'description' => 'Share one practical opportunity where collaboration or technology could create meaningful impact.',
                'prompt' => 'What problem should this community tackle first, and what would success look like?',
            ]);

        DB::table('community_topics')
            ->where('slug', Str::slug('Who are you hoping to meet at Southern Spark?'))
            ->update([
                'title' => 'Who are you hoping to meet here?',
                'slug' => Str::slug('Who are you hoping to meet here?'),
                'description' => 'Use this prompt to surface collaborations, introductions, and hallway conversations.',
                'prompt' => 'Share the kinds of people or organizations you would love to connect with during the event.',
            ]);

        DB::table('badges')
            ->where('slug', 'southern-spark-champion')
            ->update([
                'name' => 'Event Champion',
                'description' => 'Reached a standout level of participation across the app.',
            ]);

        DB::table('settings')
            ->where('key', 'brand_name')
            ->where('value', 'Southern Spark')
            ->update(['value' => 'Conference App']);
    }

    public function down(): void
    {
        DB::table('community_topics')
            ->where('slug', 'introduce-yourself')
            ->update([
                'description' => 'Start here. Share who you are, what brought you to Southern Spark, and who you hope to meet.',
                'prompt' => 'Tell the community your role, what you are building or learning, and one connection you would love to make this week.',
            ]);

        DB::table('community_topics')
            ->where('slug', Str::slug('What challenge should this community prioritize?'))
            ->update([
                'title' => 'What AI use case should Mississippi prioritize?',
                'slug' => Str::slug('What AI use case should Mississippi prioritize?'),
                'description' => 'Share one practical opportunity where AI could create meaningful impact across the state.',
                'prompt' => 'What problem should Mississippi tackle first with AI, and what would success look like?',
            ]);

        DB::table('community_topics')
            ->where('slug', Str::slug('Who are you hoping to meet here?'))
            ->update([
                'title' => 'Who are you hoping to meet at Southern Spark?',
                'slug' => Str::slug('Who are you hoping to meet at Southern Spark?'),
                'description' => 'Use this prompt to surface collaborations, introductions, and hallway conversations.',
                'prompt' => 'Share the kinds of people or organizations you would love to connect with during the conference.',
            ]);

        DB::table('badges')
            ->where('slug', 'southern-spark-champion')
            ->update([
                'name' => 'Southern Spark Champion',
                'description' => 'Reached a standout level of participation across the app.',
            ]);

        DB::table('settings')
            ->where('key', 'brand_name')
            ->where('value', 'Conference App')
            ->update(['value' => 'Southern Spark']);
    }
};
