<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_speaker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['session_id', 'user_id']);
        });

        $existingSpeakerLinks = DB::table('sessions')
            ->whereNotNull('speaker_user_id')
            ->select('id', 'speaker_user_id')
            ->get();

        $now = now();

        foreach ($existingSpeakerLinks as $link) {
            DB::table('session_speaker')->updateOrInsert(
                [
                    'session_id' => $link->id,
                    'user_id' => $link->speaker_user_id,
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('session_speaker');
    }
};
